<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Soal;
use App\Models\Ujian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UjianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.ujian.index');
    }

    public function data()
    {
        $ujians = Ujian::orderBy('created_at', 'desc');

        return datatables()
            ->eloquent($ujians)
            ->addIndexColumn()
            ->addColumn('nama', function ($ujians)
            {
                $text = '<a href="javascript:void(0);" onclick="detailForm(`' . route('admin.ujian.show', $ujians->id) . '`)">' . $ujians->nama;
                if ($ujians->isPublished) {
                    $text .= ' <span class="badge badge-success">Published</span>';
                }
                $text .= '</a>';
                return $text;
            })
            ->addColumn('waktu_pengerjaan', function ($ujians)
            {
                return Carbon::parse($ujians->waktu_mulai)->isoFormat('D MMMM Y HH:mm:ss') . ' - <br>' . Carbon::parse($ujians->waktu_akhir)->isoFormat('D MMMM Y HH:mm:ss');
            })
            ->addColumn('lama_pengerjaan', function ($ujians)
            {
                return '<span class="badge badge-warning">' . $ujians->lama_pengerjaan . '</span>';
            })
            ->addColumn('jenis_ujian', function ($ujians)
            {
                switch ($ujians->jenis_ujian) {
                    case 'mtk':
                        return 'Matematika';
                        break;
                    case 'skd':
                        return 'SKD';
                        break;
                    case 'lainnya':
                        return 'LAINNYA';
                        break;

                    default:
                        return 'LAINNYA';
                        break;
                }
            })
            ->addColumn('aksi', function ($ujians) {
                $text = '';
                if (!$ujians->isPublished && auth()->user()->hasRole('admin')) {
                    $text .= '<button onclick="editData(`' . route('admin.ujian.update', $ujians->id) . '`)" type="button" class="btn btn-outline-warning m-1"><i class="fa fa-edit"></i></button>
                    <button onclick="deleteData(`' . route('admin.ujian.destroy', $ujians->id) . '`)" type="button" class="btn btn-outline-danger m-1"><i class="fa fa-trash-alt"></i></button>';
                }
                $text .= ' <a href="' . route('admin.ujian.soal.index', $ujians->id) . '" type="button" class="btn btn-outline-info m-1"><i class="fa fa-eye"></i></a>';

                if (auth()->user()->hasRole('admin')) {
                    $text .= '<button onclick="duplicateUjian(`' . route('admin.ujian.duplicate', $ujians->id) . '`)" type="button" class="btn btn-outline-success m-1"><i class="fa fa-copy"></i></button>';
                }

                return $text;
            })
            ->rawColumns(['nama', 'aksi', 'lama_pengerjaan', 'waktu_pengerjaan'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            return 404;
        }

        $request->validate([
            'nama' => 'required',
            'jenis_ujian' => 'required',
            'waktu_mulai' => 'required',
            'waktu_akhir' => 'required',
            'waktu_pengumuman' => 'required',
            'lama_pengerjaan' => 'required|min:0',
            'jumlah_soal' => 'required|min:0',
            'tipe_ujian' => 'required',
            'tampil_kunci' => 'required',
            'tampil_nilai' => 'required',
            'tampil_poin' => 'required',
            'random' => 'required',
            'random_pilihan' => 'required',
        ]);

        $ujian = new Ujian();
        $ujian->nama = $request->nama;
        $ujian->jenis_ujian = $request->jenis_ujian;
        $ujian->deskripsi = $request->deskripsi;
        $ujian->peraturan = $request->peraturan;
        $ujian->waktu_mulai = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->waktu_mulai)));
        $ujian->waktu_akhir = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->waktu_akhir)));
        $ujian->waktu_pengumuman = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->waktu_pengumuman)));
        $ujian->lama_pengerjaan = $request->lama_pengerjaan;
        $ujian->jumlah_soal = $request->jumlah_soal;
        $ujian->tipe_ujian = $request->tipe_ujian;
        $ujian->tampil_kunci = $request->tampil_kunci;
        $ujian->tampil_nilai = $request->tampil_nilai;
        $ujian->tampil_poin = $request->tampil_poin;
        $ujian->random = $request->random;
        $ujian->random_pilihan = $request->random_pilihan;

        $ujian->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ujian = Ujian::with('soal')->find($id);
        $ujian['waktu_mulai'] = Carbon::parse($ujian['waktu_mulai'])->isoFormat('D MMMM Y HH:mm:ss');
        $ujian['waktu_akhir'] = Carbon::parse($ujian['waktu_akhir'])->isoFormat('D MMMM Y HH:mm:ss');
        $ujian['waktu_pengumuman'] = Carbon::parse($ujian['waktu_pengumuman'])->isoFormat('D MMMM Y HH:mm:ss');

        return response()->json($ujian);
    }

    public function preview($id) {
        $ujian = Ujian::findOrFail($id);

        if ($ujian->jenis_ujian == 'skd') {
            $preparation = Soal::with('jawaban')->where('ujian_id', $id)
                        ->whereIn('jenis_soal', ['twk', 'tiu', 'tkp'])
                        ->orderByRaw('FIELD(jenis_soal,"twk", "tiu", "tkp")')
                        ->orderBy('created_at', 'asc');
        } else {
            $preparation = Soal::with('jawaban')->where('ujian_id', $id);
        }
        // $preparation = Soal::with('jawaban')->where('ujian_id', $id);
        $soal = $preparation->paginate(1, ['*'], 'no');
        return view('views_user.ujian.pembahasan', compact('soal', 'ujian'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'jenis_ujian' => 'required',
            'waktu_mulai' => 'required',
            'waktu_akhir' => 'required',
            'waktu_pengumuman' => 'required',
            'lama_pengerjaan' => 'required|min:0',
            'jumlah_soal' => 'required|min:0',
            'tipe_ujian' => 'required',
            'tampil_kunci' => 'required',
            'tampil_nilai' => 'required',
            'tampil_poin' => 'required',
            'random' => 'required',
            'random_pilihan' => 'required',
        ]);

        $ujian = Ujian::findOrFail($id);

        $ujian->nama = $request->nama;
        $ujian->jenis_ujian = $request->jenis_ujian;
        $ujian->deskripsi = $request->deskripsi;
        $ujian->peraturan = $request->peraturan;
        $ujian->waktu_mulai = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->waktu_mulai)));
        $ujian->waktu_akhir = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->waktu_akhir)));
        $ujian->waktu_pengumuman = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $request->waktu_pengumuman)));
        $ujian->lama_pengerjaan = $request->lama_pengerjaan;
        $ujian->jumlah_soal = $request->jumlah_soal;
        $ujian->tipe_ujian = $request->tipe_ujian;
        $ujian->tampil_kunci = $request->tampil_kunci;
        $ujian->tampil_nilai = $request->tampil_nilai;
        $ujian->tampil_poin = $request->tampil_poin;
        $ujian->random = $request->random;
        $ujian->random_pilihan = $request->random_pilihan;
        $ujian->update();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            return 404;
        }

        $ujian = Ujian::findOrFail($id);
        $ujian->delete();

        return response(null, 204);
    }

    public function publish($id)
    {
        $ujian = Ujian::with('soal')->findorFail($id);
        if ($ujian->soal->count() == $ujian->jumlah_soal) {
            $ujian->isPublished = $ujian->isPublished ? 0 : 1;

            $ujian->update();

            return response()->json('Data berhasil disimpan', 200);
        }
        return response()->json('Tidak dapat mempublish', 300);
    }

    public function duplicate($id_ujian) {
        $ujian = Ujian::with('soal', 'soal.jawaban')->findOrFail($id_ujian);

        $duplicateUjian = $ujian->replicate();
        $duplicateUjian->nama  = $ujian->nama . '-DUPLICATE';
        $duplicateUjian->isPublished = false;
        $duplicateUjian->save();

        foreach ($duplicateUjian->soal as $soal) {
            $duplicateSoal = $soal->replicate();
            $duplicateSoal->ujian_id = $duplicateUjian->id;
            $duplicateSoal->save();
            foreach ($soal->jawaban as $jawaban) {
                $duplicateJawaban = $jawaban->replicate();
                $duplicateJawaban->soal_id = $duplicateSoal->id;
                $duplicateJawaban->save();
                if ($jawaban->id == $duplicateSoal->kunci_jawaban) {
                    $duplicateSoal->kunci_jawaban = $duplicateJawaban->id;
                    $duplicateSoal->save();
                }
            }
        }
        return response()->json('Ujian berhasil diduplikat', 200);
    }
}
