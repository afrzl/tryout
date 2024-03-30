<?php

namespace App\Http\Controllers\Admin;

use App\Models\Soal;
use App\Models\Ujian;
use App\Models\Jawaban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;

class SoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $ujian = Ujian::with('soal')->find($id);
        if (! $ujian) {
            abort(404);
        }
        if ($ujian->jenis_ujian == 'skd') {
            $twk = $ujian->soal->where('jenis_soal', 'twk');
            $tiu = $ujian->soal->where('jenis_soal', 'tiu');
            $tkp = $ujian->soal->where('jenis_soal', 'tkp');
            return view('admin.soal.index', compact('ujian', 'twk', 'tiu', 'tkp'));
        }
        return view('admin.soal.index', compact('ujian'));
    }

    public function data(Request $request, $id)
    {
        $soals = Soal::where('ujian_id', $id)
                ->with('jawaban', 'ujian')
                ->orderBy('created_at', 'asc');

        return datatables()
            ->of($soals)
            ->addIndexColumn()
            ->addColumn('soal', function ($soals)
            {
                $soal = $soals->soal . '</br><table>';
                foreach ($soals->jawaban as $key => $jawaban) {
                    $soal .= '<tr><td style="border: none">';
                    if ($jawaban->id == $soals->kunci_jawaban) {
                        $soal .= '<span class="badge badge-success">' . chr($key+65) . '. </span>';
                    } else {
                        $soal .= chr($key+65) . '.';
                    }

                    if ($soals->jenis_soal == 'tkp') {
                        $soal .= '</td><td style="border: none"><font color="green">(' . $jawaban->point . ')</font> ' . $jawaban->jawaban . '</td></tr>';
                    } else {
                        $soal .= '</td><td style="border: none">' . $jawaban->jawaban . '</td></tr>';
                    }
                }
                $soal .= '</table>';

                return $soal;
            })
            ->addColumn('jenis_soal', function ($soals)
            {
                return strtoupper($soals->jenis_soal);
            })
            ->addColumn('point', function ($soals)
            {
                if ($soals->jenis_soal == 'tkp') {
                    return '-';
                } else {
                    return (
                        '<span class="badge badge-success">Benar: ' . $soals->poin_benar . '</span>' .
                        '<span class="badge badge-info">Kosong: ' . $soals->poin_kosong . '</span>' .
                        '<span class="badge badge-danger">Salah: ' . $soals->poin_salah . '</span>'
                    );
                }
            })
            ->addColumn('aksi', function ($soals) {
                if ($soals->ujian->isPublished) {
                    return '<span class="badge badge-success">Published</span>';
                } else {
                    return '
                        <a href="' . route('admin.soal.edit', $soals->id) . '" type="button" class="btn btn-outline-warning"><i class="fa fa-edit"></i></a>
                        <button onclick="deleteData(`' .
                        route('admin.soal.destroy', $soals->id) .
                        '`)" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-alt"></i></button>
                    ';
                }
            })
            ->filter(function ($soals) use ($request) {
                if ($request->get('jenis_soal') == 'twk' || $request->get('jenis_soal') == 'tiu' || $request->get('jenis_soal') == 'tkp') {
                    $soals->where('jenis_soal', $request->jenis_soal)->orderBy('created_at', 'asc');
                }
                if (!empty($request->get('search'))) {
                    $soals->where(function($w) use($request){
                        $search = $request->get('search');
                        $w->orWhere('soal', 'LIKE', "%$search%");
                    });
                }
            })
            ->rawColumns(['aksi', 'soal', 'point'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $soal = new Soal();
        $ujian = Ujian::with('soal')->find($id);
        if (! $ujian) {
            abort(404);
        }
        $action = 'admin.ujian.soal.store';
        return view('admin.soal.form', compact('ujian', 'soal', 'action'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'jenis_soal' => 'required_if:jenis_ujian,skd',
            'soal' => 'required',
            'jawaban.*' => 'required',
            'kunci_jawaban' => 'required_unless:jenis_ujian,skd|required_unless:jenis_soal,tkp',
            'point.*' => 'required_if:jenis_ujian,tkp',
        ], [
            'jenis_soal.required_if' => 'Jenis soal tidak boleh kosong.',
            'soal.required' => 'Soal tidak boleh kosong.',
            'jawaban.*.required' => 'Jawaban tidak boleh kosong.',
            'kunci_jawaban.required_if' => 'Kunci jawaban tidak boleh kosong.',
            'point.*.required_if' => 'Point tidak boleh kosong.'
        ]);

        $ujian = Ujian::with('soal')->findOrFail($id);
        if ($ujian->jumlah_soal == $ujian->soal->count()) {
            return redirect()->back()->withInput()->with('message','Soal sudah penuh.');
        }

        if ($ujian->jenis_ujian == 'skd') {
            if ($request->jenis_soal == 'twk') {
                if ($ujian->soal->where('jenis_soal', $request->jenis_soal)->count() == 30) {
                    return redirect()->back()->withInput()->with('message','Soal sudah penuh.');
                }
            } elseif ($request->jenis_soal == 'tiu') {
                if ($ujian->soal->where('jenis_soal', $request->jenis_soal)->count() == 35) {
                    return redirect()->back()->withInput()->with('message','Soal sudah penuh.');
                }
            } elseif ($request->jenis_soal == 'tkp') {
                if ($ujian->soal->where('jenis_soal', $request->jenis_soal)->count() == 45) {
                    return redirect()->back()->withInput()->with('message','Soal sudah penuh.');
                }
            }
        }

        $soal = new Soal();
        $soal->ujian_id = $id;
        $soal->soal = $request->soal;
        $soal->jenis_soal = $request->jenis_ujian == 'skd' ? $request->jenis_soal : null;
        if ($request->jenis_soal != 'tkp') {
            $soal->poin_benar = $request->nilai_benar ? $request->nilai_benar : 0;
            $soal->poin_salah = $request->nilai_salah ? $request->nilai_salah : 0;
            $soal->poin_kosong = $request->nilai_kosong ? $request->nilai_kosong : 0;
        }
        $soal->pembahasan = $request->pembahasan;
        $soal->save();

        foreach ($request->jawaban as $key => $jawaban) {
            $jawaban = new Jawaban();
            $jawaban->soal_id = $soal->id;
            $jawaban->jawaban = $request->jawaban[$key];
            if ($request->jenis_soal == 'tkp') {
                $jawaban->point = $request->point[$key] ? $request->point[$key] : 0;
            }
            $jawaban->save();

            if ($request->jenis_soal != 'tkp') {
                if ($key == $request->kunci_jawaban) {
                    $inputKunci = Soal::findOrFail($soal->id);
                    $inputKunci->kunci_jawaban = $jawaban->id;
                    $inputKunci->update();
                }
            }
        }

        return redirect()->route('admin.ujian.soal.index', $id)->with('message','Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Soal $soal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $soal = Soal::with('ujian', 'jawaban')->find($id);
        if (! $soal) {
            abort(404);
        }
        if ($soal->ujian->isPublished) {
            return redirect()->route('admin.ujian.soal.index', $soal->ujian_id);
        }
        $ujian = $soal->ujian;
        $action = 'admin.soal.update';
        return view('admin.soal.form', compact('soal', 'ujian', 'action'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_soal' => 'required_if:jenis_ujian,skd',
            'soal' => 'required',
            'jawaban.*' => 'required',
            'kunci_jawaban' => 'required_unless:jenis_ujian,skd|required_unless:jenis_soal,tkp',
            'point.*' => 'required_if:jenis_ujian,tkp',
        ], [
            'jenis_soal.required_if' => 'Jenis soal tidak boleh kosong.',
            'soal.required' => 'Soal tidak boleh kosong.',
            'jawaban.*.required' => 'Jawaban tidak boleh kosong.',
            'kunci_jawaban.required_if' => 'Kunci jawaban tidak boleh kosong.',
            'point.*.required_if' => 'Point tidak boleh kosong.'
        ]);

        $soal = Soal::with('jawaban')->findOrFail($id);
        $soal->soal = $request->soal;
        $soal->jenis_soal = $request->jenis_ujian == 'skd' ? $request->jenis_soal : null;
        $soal->pembahasan = $request->pembahasan;
        if ($soal->jenis_soal != 'tkp') {
            $soal->poin_benar = $request->nilai_benar;
            $soal->poin_salah = $request->nilai_salah;
            $soal->poin_kosong = $request->nilai_kosong;
            $soal->kunci_jawaban = $request->kunci_jawaban;
        }
        $soal->update();

        foreach ($request->id_jawaban as $key => $id_jawaban) {
            $jawaban = Jawaban::findOrFail($id_jawaban);
            if ($request->jenis_soal != 'tkp') {
                $jawaban->jawaban = $request->jawaban[$key];
            }
            $jawaban->point = $request->point[$key];
            $jawaban->update();
        }

        return redirect()->route('admin.ujian.soal.index', $soal->ujian_id)->with('message','Data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $soal = Soal::find($id);
        $soal->delete();

        return response(null, 204);
    }
}
