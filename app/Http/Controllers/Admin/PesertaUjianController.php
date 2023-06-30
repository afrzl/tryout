<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Soal;
use App\Models\Ujian;
use App\Models\Pembelian;
use App\Models\JawabanPeserta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PesertaUjianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('peserta_ujian.index');
    }

    public function data()
    {
        $ujians = Ujian::with('pembelian')->where('isPublished', 1)->orderBy('created_at', 'desc');

        return datatables()
            ->eloquent($ujians)
            ->addIndexColumn()
            ->addColumn('waktu_pelaksanaan', function ($ujians)
            {
                return Carbon::parse($ujians->waktu_mulai)->isoFormat('D MMMM Y HH:mm:ss') . ' - <br>' . Carbon::parse($ujians->waktu_akhir)->isoFormat('D MMMM Y HH:mm:ss');
            })
            ->addColumn('jumlah_peserta', function ($ujians)
            {
                return $ujians->pembelian->where('status', 'Sukses')->count();
            })
            ->addColumn('peserta_mengerjakan', function ($ujians)
            {
                return $ujians->pembelian->where('status_pengerjaan', 'Selesai')->count();
            })
            ->addColumn('status', function ($ujians)
            {
                if ($ujians->waktu_mulai <= date("Y-m-d H:i:s") && $ujians->waktu_akhir >= date("Y-m-d H:i:s")) {
                    return '<span class="badge badge-success">Aktif</span>';
                }
                if ($ujians->waktu_mulai > date("Y-m-d H:i:s")) {
                    return '<span class="badge badge-warning">Akan Datang</span>';
                }
                if ($ujians->waktu_akhir < date("Y-m-d H:i:s")) {
                    return '<span class="badge badge-danger">Terlaksana</span>';
                }
            })
            ->addColumn('aksi', function ($ujians) {
                return '
                    <a href="' .
                    route('admin.peserta_ujian.show', $ujians->id) .
                    '" type="button" class="btn btn-outline-info"><i class="fa fa-chevron-right"></i></a>
                ';
            })
            ->rawColumns(['aksi', 'waktu_pelaksanaan', 'status'])
            ->make(true);
    }

    public function showData($id)
    {
        $peserta = Pembelian::with('user', 'user.sessions', 'jawabanPeserta.soal')
                    ->where('ujian_id', $id)
                    ->where('status', 'Sukses')
                    ->orderBy('created_at', 'desc');

        return datatables()
            ->eloquent($peserta)
            ->addIndexColumn()
            ->addColumn('email', function ($peserta)
            {
                return $peserta->user->email;
            })
            ->addColumn('created_at', function ($peserta)
            {
                return Carbon::parse($peserta->created_at)->isoFormat('D MMMM Y HH:mm:ss');
            })
            ->addColumn('waktu_pengerjaan', function ($peserta)
            {
                if ($peserta->status_pengerjaan == 'Selesai') {
                    $waktu_mulai = Carbon::parse($peserta->waktu_mulai_pengerjaan);
                    $waktu_selesai = Carbon::parse($peserta->waktu_selesai_pengerjaan);
                    return $waktu_selesai->diff($waktu_mulai)->format('%H:%I:%S');
                } else {
                    return '-';
                }
            })
            ->addColumn('status_pengerjaan', function ($peserta)
            {
                $class = $peserta->status_pengerjaan == 'Selesai' ? 'success' : ($peserta->status_pengerjaan == 'Masih dikerjakan' ? 'warning' : 'danger');

                return '<span class="badge badge-' . $class . '">' . $peserta->status_pengerjaan . '</span>';
            })
            ->addColumn('nilai', function ($peserta)
            {
                if ($peserta->status_pengerjaan == 'Selesai') {
                    $benar = 0;
                    foreach ($peserta->jawabanPeserta as $key => $jawabanPeserta) {
                        if ($jawabanPeserta->jawaban_id == $jawabanPeserta->soal->id_kunci_jawaban) {
                            $benar++;
                        }
                    }
                    return '<span class="badge badge-success">' . round($benar / $peserta->ujian->jumlah_soal * 100, 2) . '</span>';
                } else {
                    return '<span class="badge badge-danger">-</span>';
                }
            })
            ->addColumn('aksi', function ($peserta) {
                if ($peserta->status_pengerjaan == 'Selesai') {
                    return '
                        <a href="' .
                        route('admin.peserta_ujian.show_peserta', $peserta->id) .
                        '" type="button" class="btn btn-outline-info"><i class="fa fa-chevron-right"></i></a>
                    ';
                }
            })
            ->rawColumns(['aksi', 'status_pengerjaan', 'nilai'])
            ->make(true);
    }

    public function showDataPeserta($id)
    {
        $jawaban = JawabanPeserta::with(['pembelian', 'soal', 'soal.jawaban'])
                    ->where('pembelian_id', $id)
                    ->get();

        return datatables()
            ->of($jawaban)
            ->addIndexColumn()
            ->addColumn('soal', function ($jawaban)
            {
                $text = $jawaban->soal->soal . '</br><table>';
                foreach ($jawaban->soal->jawaban as $key => $item) {
                    $text .= '<tr><td style="border: none">';
                    if ($item->id == $jawaban->jawaban_id) {
                        $text .= '<span class="badge badge-warning">' . chr($key+65) . '. </span>';
                    } else {
                        $text .= chr($key+65) . '.';
                    }
                    $text .= '</td><td style="border: none">';
                    if ($item->id == $jawaban->soal->id_kunci_jawaban) {
                        $text .= '<font color="red"><b>'. $item->jawaban .'</b></font>';
                    } else {
                        $text .= $item->jawaban;
                    }
                    $text .= '</td></tr>';
                }
                $text .= '</table>';

                return $text;
            })
            ->addColumn('status', function ($jawaban) {
                if ($jawaban->jawaban_id != 0) {
                    if ($jawaban->soal->id_kunci_jawaban == $jawaban->jawaban_id) {
                        return '<span class="badge badge-success">Benar</span>';
                    } else {
                        return '<span class="badge badge-danger">Salah</span>';
                    }
                } else {
                    return '<span class="badge badge-warning">Tidak dijawab</span>';
                }
            })
            ->rawColumns(['status', 'soal'])
            ->make(true);
    }

    public function refresh($id) {
        $pembelian = Pembelian::with('ujian')->where('ujian_id', $id)->get();
        $ujian = Ujian::with('pembelian')->find($id);
        $d = explode(':', $ujian->lama_pengerjaan);
        $lama_pengerjaan =  ($d[0] * 3600) + ($d[1] * 60) + $d[2];
        foreach ($ujian->pembelian as $key => $value) {
            if ($value->status_pengerjaan == 'Masih dikerjakan') {
                $waktu_akhir = Carbon::parse($ujian->pembelian[$key]->waktu_mulai_pengerjaan)->addSeconds($lama_pengerjaan);
                if ($waktu_akhir->isPast()) {
                    $pembelian = Pembelian::findOrFail($value->id);
                    $pembelian->status_pengerjaan = "Selesai";
                    $pembelian->waktu_selesai_pengerjaan = $waktu_akhir;
                    $pembelian->update();
                }
            }
        }

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ujian = Ujian::find($id);
        return view('peserta_ujian.show', compact('ujian'));
    }

    public function showPeserta($id)
    {
        // $pembelian = Pembelian::with([
        //                 'ujian.soal.jawaban',
        //                 'ujian.soal.jawabanPeserta' => function ($query) use ($id)
        //                 {
        //                     $query->where('jawaban_peserta.pembelian_id', $id);
        //                 }
        //                 ])
        //             ->find($id);
        // return $pembelian->ujian->soal;

        $pembelian = Pembelian::with('ujian', 'user', 'jawabanPeserta.soal')
                    ->find($id);

        $benar = 0;
        foreach ($pembelian->jawabanPeserta as $key => $jawabanPeserta) {
            if ($jawabanPeserta->jawaban_id == $jawabanPeserta->soal->id_kunci_jawaban) {
                $benar++;
            }
        }

        return view('peserta_ujian.showPeserta', compact('pembelian', 'benar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ujian $ujian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ujian $ujian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ujian $ujian)
    {
        //
    }
}
