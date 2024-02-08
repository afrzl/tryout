<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Soal;
use App\Models\Ujian;
use GuzzleHttp\Client;
use App\Models\Pembelian;
use App\Models\UjianUser;
use Illuminate\Http\Request;
use App\Models\JawabanPeserta;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class PesertaUjianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.peserta_ujian.index');
    }

    public function data()
    {
        $ujians = Ujian::with('ujianUser')->where('isPublished', 1)->orderBy('created_at', 'desc');

        return datatables()
            ->eloquent($ujians)
            ->addIndexColumn()
            ->addColumn('waktu_pelaksanaan', function ($ujians)
            {
                return Carbon::parse($ujians->waktu_mulai)->isoFormat('D MMMM Y HH:mm:ss') . ' - <br>' . Carbon::parse($ujians->waktu_akhir)->isoFormat('D MMMM Y HH:mm:ss');
            })
            ->addColumn('jumlah_peserta', function ($ujians)
            {
                return $ujians->ujianUser->where('is_first', 1)->count();
            })
            ->addColumn('peserta_mengerjakan', function ($ujians)
            {
                return $ujians->ujianUser->where('is_first', 1)->where('status', '2')->count();
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

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ujian = Ujian::find($id);
        return view('admin.peserta_ujian.show', compact('ujian'));
    }

    public function showData($id)
    {
        $peserta = UjianUser::with('ujian', 'user', 'user.sessions', 'jawabanPeserta.soal')
                    ->where('ujian_id', $id)
                    ->where('is_first', 1)
                    ->orderBy('created_at', 'desc');

        return datatables()
            ->eloquent($peserta)
            ->addIndexColumn()
            ->addColumn('nama', function ($peserta)
            {
                return $peserta->user->name;
            })
            ->addColumn('email', function ($peserta)
            {
                return $peserta->user->email;
            })
            ->addColumn('waktu_pengerjaan', function ($peserta)
            {
                if ($peserta->status == '2') {
                    $waktu_mulai = Carbon::parse($peserta->waktu_mulai);
                    $waktu_akhir = Carbon::parse($peserta->waktu_akhir);
                    return $waktu_akhir->diffInMinutes($waktu_mulai) . ' Menit';
                } else {
                    return '-';
                }
            })
            ->addColumn('status_pengerjaan', function ($peserta)
            {
                $class = $peserta->status == '2' ? 'success' : ($peserta->status == '1' ? 'warning' : 'danger');

                return '<span class="badge badge-' . $class . '">' . $peserta->status == 1 ? 'Sedang Mengerjakan' : 'Selesai' . '</span>';
            })
            ->addColumn('nilai', function ($peserta)
            {
                if ($peserta->status == '2') {
                    if ($peserta->ujian->jenis_ujian == 'skd') {
                        return "<span class='badge badge-success'>TWK : {$peserta->nilai_twk}</span>
                                <br><span class='badge badge-success'>TIU : {$peserta->nilai_tiu}</span>
                                <br><span class='badge badge-success'>TKP : {$peserta->nilai_tkp}</span>
                                <br><span class='badge badge-success'>Total : {$peserta->nilai}</span>";
                    } else {
                        return '<span class="badge badge-success">' . $peserta->nilai . '</span>';
                    }
                } else {
                    return '<span class="badge badge-danger">-</span>';
                }
            })
            ->addColumn('aksi', function ($peserta) {
                if ($peserta->status == '2') {
                    return '
                        <a href="' .
                        route('admin.peserta_ujian.show_peserta', $peserta->id) .
                        '" type="button" class="btn btn-outline-info"><i class="fa fa-chevron-right"></i></a>
                    ';
                }
            })
            ->rawColumns(['aksi', 'email', 'waktu_pengerjaan', 'status_pengerjaan', 'nilai'])
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
        $ujianUser = UjianUser::where('ujian_id', $id)->get();
        foreach ($ujianUser as $key => $value) {
            if ($value->status != '2') {
                if (Carbon::parse($value->waktu_akhir)->isPast()) {
                    $client = new Client();
                    $tokenRequest = $client->request('PUT', url('/ujian/selesaiujian/' . $value->id),);
                    $response = Route::dispatch($tokenRequest);
                    if($response->getStatusCode() == 200){
                        continue;
                    }
                    // $request = Request::create(APPLICATION_URL . 'ujian.selesai', 'PUT', []);
                    // $request->headers->set('X-CSRF-TOKEN', csrf_token());
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

        return view('admin.peserta_ujian.showPeserta', compact('pembelian', 'benar'));
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
