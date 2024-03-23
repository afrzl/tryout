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
use Illuminate\Database\Eloquent\Collection;

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
        $ujians = Ujian::with(['ujianUser', 'paketUjian', 'paketUjian.pembelian' => function($query) {
                                            $query->where('status', 'Sukses');
                                        }])
                ->orderBy('created_at', 'desc');

        return datatables()
            ->eloquent($ujians)
            ->addIndexColumn()
            ->addColumn('waktu_pelaksanaan', function ($ujians)
            {
                return Carbon::parse($ujians->waktu_mulai)->isoFormat('D MMMM Y HH:mm:ss') . ' - <br>' . Carbon::parse($ujians->waktu_akhir)->isoFormat('D MMMM Y HH:mm:ss');
            })
            ->addColumn('jumlah_peserta', function ($ujians)
            {
                // return $ujians->ujianUser->where('is_first', 1)->count();
                $user = new Collection();
                foreach ($ujians->paketUjian as $paket) {
                    foreach ($paket->pembelian as $pembelian) {
                        $user[] = $pembelian;
                    }
                }
                $user = $user->unique('user_id')->values();
                return $user->count();
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
                    ->orderBy('created_at', 'desc')
                    ->get();

        $ujians = Ujian::with(['ujianUser', 'paketUjian.pembelian' => function($query) {
                                            $query->where('status', 'Sukses');
                                        }])
                ->findOrFail($id);
        foreach ($ujians->paketUjian as $paket) {
            foreach ($paket->pembelian as $pembelian) {
                $pembelian->status = '0';
                $peserta[] = $pembelian;
            }
        }
        $peserta = $peserta->unique('user_id')->values();

        return datatables()
            ->collection($peserta)
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

                return '<span class="badge badge-' . $class . '">' . ($peserta->status == 1 ? 'Sedang Mengerjakan' : ($peserta->status == 0 ? 'Belum Mengerjakan' : 'Selesai')) . '</span>';
            })
            ->addColumn('nilai', function ($peserta)
            {
                if ($peserta->status == '2') {
                    if ($peserta->ujian->jenis_ujian == 'skd') {
                        return "<span class='badge badge-success'>TWK : {$peserta->nilai_twk}</span>
                                <br><span class='badge badge-success'>TIU : {$peserta->nilai_tiu}</span>
                                <br><span class='badge badge-success'>TKP : {$peserta->nilai_tkp}</span>
                                <br><span class='badge badge-info'>Total : {$peserta->nilai}</span>";
                    } else {
                        return '<span class="badge badge-success">' . $peserta->nilai . '</span>';
                    }
                } else {
                    return '<span class="badge badge-danger">-</span>';
                }
            })
            ->addColumn('aksi', function ($peserta) {
                $text = '';
                if ($peserta->status == '2') {
                    $text .= '
                        <a href="' .
                        route('admin.peserta_ujian.show_peserta', $peserta->id) .
                        '" type="button" class="btn btn-outline-info"><i class="fa fa-chevron-right"></i></a>';
                }
                if ($peserta->status == '1' || $peserta->status == '2') {
                    if (auth()->user()->hasRole('admin')) {
                        $text .= '<button onclick="deleteData(`' .
                        route('admin.peserta_ujian.destroy', [$peserta->ujian_id, $peserta->user_id]) .
                        '`)" type="button" class="btn btn-outline-danger ml-1"><i class="fa fa-rotate-right"></i></button>';
                    }
                }
                return $text;
            })
            ->rawColumns(['aksi', 'email', 'waktu_pengerjaan', 'status_pengerjaan', 'nilai'])
            ->make(true);
    }

    public function showPeserta($id)
    {
        $data = UjianUser::with('ujian', 'user', 'jawabanPeserta.soal.jawaban')
                    ->find($id);

        return view('admin.peserta_ujian.showPeserta', compact('data'));
    }

    public function showDataPeserta($id)
    {
        $data = UjianUser::with('jawabanPeserta.soal.jawaban')
                    ->find($id);
        $data = $data->jawabanPeserta;

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->addColumn('soal', function ($data)
            {
                $text = $data->soal->soal . '</br><table>';
                foreach ($data->soal->jawaban as $key => $item) {
                    $text .= '<tr><td style="border: none">';
                    if ($item->id == $data->soal->kunci_jawaban) {
                        $text .= '<span class="badge badge-success">' . chr($key+65) . '. </span>';
                    } else {
                        $text .= chr($key+65) . '.';
                    }
                    $text .= '</td><td style="border: none">';
                    if ($item->id == $data->jawaban_id) {
                        $text .= '<font color="red"><b>'. $item->jawaban .'</b></font>';
                    } else {
                        $text .= $item->jawaban;
                    }

                    if ($data->soal->jenis_soal == 'tkp') {
                        $text .= ' <font color="green"><i>('. $item->point .')</i></font>';
                    }

                    $text .= '</td></tr>';
                }
                $text .= '</table>';

                return $text;
            })
            ->addColumn('point', function($data) {
                return $data->poin;
            })
            ->addColumn('status', function ($data) {
                if ($data->soal->jenis_soal == 'tkp') {
                    return '-';
                }

                if ($data->jawaban_id != NULL) {
                    if ($data->soal->kunci_jawaban == $data->jawaban_id) {
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
    public function destroy($ujian_id, $user_id)
    {
        $ujianUser = UjianUser::where('ujian_id', $ujian_id)->where('user_id', $user_id);
        $ujianUser->delete();

        return response(null, 204);
    }
}
