<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use App\Models\Ujian;
use App\Models\Jawaban;
use Illuminate\Http\Request;

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
        return view('admin.soal.index', compact('ujian'));
    }

    public function data($id)
    {
        $soals = Soal::where('ujian_id', $id)
                ->with('jawaban', 'ujian')
                ->orderBy('created_at', 'asc')
                ->get();

        return datatables()
            ->of($soals)
            ->addIndexColumn()
            ->addColumn('soal', function ($soals)
            {
                $soal = $soals->soal . '</br><table>';
                foreach ($soals->jawaban as $key => $jawaban) {
                    $soal .= '<tr><td style="border: none">';
                    if ($jawaban->isKunci) {
                        $soal .= '<span class="badge badge-success">' . chr($key+65) . '. </span>';
                    } else {
                        $soal .= chr($key+65) . '.';
                    }
                    $soal .= '</td><td style="border: none">' . $jawaban->jawaban . '</td></tr>';
                }
                $soal .= '</table>';

                return $soal;
            })
            ->addColumn('aksi', function ($soals) {
                if ($soals->ujian->isPublished) {
                    return '<span class="badge badge-success">Published</span>';
                } else {
                    return '
                        <a href="' . route('soal.edit', $soals->id) . '" type="button" class="btn btn-outline-warning"><i class="fa fa-edit"></i></a>
                        <button onclick="deleteData(`' .
                        route('soal.destroy', $soals->id) .
                        '`)" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-alt"></i></button>
                    ';
                }
            })
            ->rawColumns(['aksi', 'soal'])
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
        if ($ujian->jumlah_soal == $ujian->soal->count()) {
            return redirect()->route('admin.ujian.soal.index', $ujian->id);
        }
        $action = 'ujian.soal.store';
        return view('admin.soal.form', compact('ujian', 'soal', 'action'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {

        $request->validate([
            'soal' => 'required',
            'pilihan.*' => 'required',
            'kunci_jawaban' => 'required'
        ], [
            'soal.required' => 'Soal tidak boleh kosong.',
            'pilihan.*.required' => 'Jawaban tidak boleh ada yang kosong.',
            'kunci_jawaban.required' => 'Kunci jawaban harus ada.',
        ]);

        $soal = new Soal();
        $soal->ujian_id = $id;
        $soal->soal = $request->soal;
        $soal->save();

        foreach ($request->pilihan as $key => $pilihan) {
            $jawaban = new Jawaban();
            $jawaban->soal_id = $soal->id;
            $jawaban->jawaban = $pilihan;
            if ($request->kunci_jawaban == $request->id_pilihan[$key]) {
                $jawaban->isKunci = 1;
            }
            $jawaban->save();
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
        $soal = Soal::with('ujian')->find($id);
        if (! $soal) {
            abort(404);
        }
        $ujian = $soal->ujian;
        $action = 'soal.update';
        return view('admin.soal.form', compact('soal', 'ujian', 'action'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'soal' => 'required',
            'pilihan.*' => 'required',
            'id_jawaban.*' => 'required',
            'kunci_jawaban' => 'required',
        ]);

        $soal = Soal::with('jawaban')->findOrFail($id);
        $soal->soal = $request->soal;
        $soal->update();

        foreach ($request->id_pilihan as $key => $pilihan) {
            if (substr($pilihan, -3) != "new") {
                $jawaban = Jawaban::findOrFail($pilihan);
                $jawaban->jawaban = $request->pilihan[$key];
                $jawaban->isKunci = $request->kunci_jawaban == $pilihan ? 1 : 0;
                $jawaban->update();
            } else {
                $jawaban = new Jawaban();
                $jawaban->soal_id = $soal->id;
                $jawaban->jawaban = $request->pilihan[$key];
                $jawaban->isKunci = $request->kunci_jawaban == $pilihan ? 1 : 0;
                $jawaban->save();
            }
        }

        if ($request->has('id_deleted')) {
            foreach ($request->id_deleted as $key => $value) {
                $jawaban = Jawaban::findOrFail($value);
                $jawaban->delete();
            }
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
