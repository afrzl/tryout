<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Soal;
use App\Models\User;
use App\Models\Ujian;
use App\Models\Jawaban;
use App\Models\Session;
use App\Models\Pembelian;
use App\Models\UjianUser;
use App\Models\PaketUjian;
use Illuminate\Http\Request;
use App\Models\JawabanPeserta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class UjianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id = null)
    {
        $data = Pembelian::with([
                        'paketUjian.ujian' => function ($query) {
                            $query->where('isPublished', 1);
                        },
                        'paketUjian.ujian.ujianUser' => function ($query) {
                            $query->where('user_id', auth()->user()->id);
                        }])
                    ->where('user_id', auth()->user()->id)
                    ->where('status', 'Sukses')
                    ->latest('updated_at');
        if ($id == NULL) {
            $data = $data->get();
        } else {
            $data =  $data->where('paket_id', $id)->get();
        }

        $tryout = new collection();
        foreach ($data as $dt) {
            foreach ($dt->paketUjian->ujian as $ujian) {
                $ujian['id_paket'] = $dt->paketUjian->id;
                $ujian['nama_paket'] = $dt->paketUjian->nama;
                $tryout[] = $ujian;
            }
        }

        $tryout = $tryout->unique('id');
        return view('views_user.ujian.tryout', compact('data', 'tryout'));
    }

    public function ujian($id) {
        $ujianUser = UjianUser::findOrFail($id);
        if ($ujianUser->status == 2) {
            abort(403, 'ERROR');
        }

        $preparation = JawabanPeserta::with(['soal', 'soal.jawaban' => function ($q)
        {
            $q->inRandomOrder();
        }, 'soal.ujian'])
                ->where('ujian_user_id', $id);
        $ragu_ragu = $preparation->pluck('ragu_ragu');
        $soal = $preparation->paginate(1, ['*'], 'no');
        return view('views_user.ujian.index', compact('soal', 'ragu_ragu', 'ujianUser'));
    }

    public function pembahasan($id) {
        $ujian = Ujian::with('ujianUser')->findOrFail($id);
        if (!($ujian->ujianUser || $ujian->tampil_kunci == 1 || ($ujian->tampil_kunci == 2 && Carbon::now() > $ujian->waktu_akhir))) {
            abort(403, 'ERROR');
        }

        $preparation = Soal::with('jawaban')->where('ujian_id', $id);
        $soal = $preparation->paginate(1, ['*'], 'no');
        return view('views_user.ujian.pembahasan', compact('soal', 'ujian'));
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
        $request->validate([
            'jawaban_peserta' => 'required',
            'key' => 'required',
        ]);

        $jawaban_peserta = JawabanPeserta::with('soal')->findOrFail($request->jawaban_peserta);
        if ($jawaban_peserta === null) {
            $store = new JawabanPeserta();
            $store->pembelian_id = $request->pembelian_id;
            $store->soal_id = $request->soal_id;
            $store->jawaban_id = $request->key;
            $store->save();
        } else {
            if ($jawaban_peserta->soal->jenis_soal == 'tkp') {
                $jawaban = Jawaban::findOrFail($request->key);
                $jawaban_peserta->poin = $jawaban->point;
            } else {
                if ($request->key == $jawaban_peserta->soal->kunci_jawaban) {
                    $jawaban_peserta->poin = $jawaban_peserta->soal->poin_benar;
                } else {
                    $jawaban_peserta->poin = $jawaban_peserta->soal->poin_salah;
                }
            }

            $jawaban_peserta->jawaban_id = $request->key;
            $jawaban_peserta->update();
        }

        return response()->json('Data berhasil disimpan', 200);
    }

    public function storeRagu(Request $request, $id)
    {
        $jawaban = JawabanPeserta::find($id);
        $jawaban->ragu_ragu = $jawaban->ragu_ragu == 1 ? 0 : 1;
        $jawaban->update();

        return response()->json($jawaban, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ujian = Ujian::with(['ujianUser' => function ($query) {
            return $query->where('is_first', 1)->where('user_id', auth()->user()->id)->first();
        }], 'paketUjian')->find($id);

        if ($ujian->isPublished != 1) {
            abort(404);
        }

        $cek = false;
        foreach ($ujian->paketUjian as $paket) {
            $paketUjian = PaketUjian::with('pembelian')->find($paket->id);
            foreach ($paketUjian->pembelian as $pembelian) {
                if ($pembelian->user_id === auth()->user()->id) {
                    $cek = true;
                    break;
                }
            }
        }
        $betweenTime = Carbon::now()->between($ujian->waktu_mulai, $ujian->waktu_akhir);
        if (!$cek) {
            abort(403, 'ERROR');
        }
        return view('views_user.ujian.show', compact('ujian', 'betweenTime'));
    }

    public function sessionDestroy() {
        $session = Session::where('user_id', auth()->user()->id)
                    ->where('id', '!=', session()->getId());
        $session->delete();

        return response(200);
    }

    private function generateSoal($id) {
        $ujianUser = UjianUser::with('ujian')->findOrFail($id);
        if ($ujianUser->ujian->jenis_ujian == 'skd') {
            $twk = Soal::where('ujian_id', $ujianUser->ujian->id)
                    ->where('jenis_soal', 'twk')
                    ->limit(30)
                    ->get();
            $tiu = Soal::where('ujian_id', $ujianUser->ujian->id)
                    ->where('jenis_soal', 'tiu')
                    ->limit(35)
                    ->get();
            $tkp = Soal::where('ujian_id', $ujianUser->ujian->id)
                    ->where('jenis_soal', 'tkp')
                    ->limit(45)
                    ->get();
            if ($ujianUser->ujian->random) {
                $twk = $twk->shuffle();
                $tiu = $tiu->shuffle();
                $tkp = $tkp->shuffle();
            }
            $soal = new Collection();
            $soal = $soal->merge($twk);
            $soal = $soal->merge($tiu);
            $soal = $soal->merge($tkp);
        } else {
            $soal = Soal::where('ujian_id', $ujianUser->ujian->id)
                    ->limit($ujianUser->ujian->jumlah_soal)
                    ->get();
            $soal = $ujianUser->ujian->random ? $soal->shuffle() : $soal;
        }
        return $soal;
    }

    public function mulaiUjian($id)
    {
        $session = Session::where('user_id', auth()->user()->id)->get();
        if ($session->count() > 1) {
            return response()->json('session limit', 200);
        }

        $ujianUser = UjianUser::with('ujian')
                    ->where('ujian_id', $id)
                    ->where('user_id', auth()->user()->id)
                    ->latest()
                    ->first();

        if ($ujianUser == NULL) {
            $createUjianUser = UjianUser::create([
                'ujian_id' => $id,
                'user_id' => auth()->user()->id,
                'status' => 0,
                'is_first' => 1,
            ]);
            $ujianUser = $createUjianUser;

            $soal = $this->generateSoal($createUjianUser->id);
        } else {
            if ($ujianUser->ujian->tipe_ujian == 1) {
                return response()->json('OK|'.$ujianUser->id, 200);
            } else if($ujianUser->ujian->tipe_ujian == 2) {
                if ($ujianUser->status == 2) {
                    $createUjianUser = UjianUser::create([
                        'ujian_id' => $id,
                        'user_id' => auth()->user()->id,
                        'status' => 0,
                    ]);
                    $ujianUser = $createUjianUser;

                    $soal = $this->generateSoal($createUjianUser->id);
                } else {
                    return response()->json('OK|'.$ujianUser->id, 200);
                }
            }
        }

        if ($ujianUser->status != 1) {
            $ujianUser->status = 1;
            $ujianUser->waktu_mulai = Carbon::now();
            $ujianUser->waktu_akhir = Carbon::now()->addMinutes($ujianUser->ujian->lama_pengerjaan);
            $ujianUser->update();

            foreach ($soal as $key => $item) {
                $store = new JawabanPeserta();
                $store->ujian_user_id = $ujianUser->id;
                $store->soal_id = $item->id;
                $store->poin = $item->poin_kosong;
                $store->save();
            }
        }
        return response()->json('OK|'.$ujianUser->id, 200);
    }

    public function selesaiUjian($id)
    {
        $ujianUser = UjianUser::with('ujian', 'jawabanPeserta', 'jawabanPeserta.soal')->findOrFail($id);

        if ($ujianUser->status == 1) {
            $ujianUser->status = 2;
            $ujianUser->waktu_akhir = date('Y-m-d H:i:s');
            $ujianUser->nilai = $ujianUser->jawabanPeserta->sum('poin');
            if ($ujianUser->ujian->jenis_ujian == 'skd') {
                $ujianUser->nilai_twk = $ujianUser->jawabanPeserta->splice(0,30)->sum('poin');
                $ujianUser->nilai_tiu = $ujianUser->jawabanPeserta->splice(0,35)->sum('poin');
                $ujianUser->nilai_tkp = $ujianUser->jawabanPeserta->splice(0,45)->sum('poin');
            } else {
                $jml_benar = 0;
                $jml_salah = 0;
                foreach ($ujianUser->jawabanPeserta as $item) {
                    if ($item->jawaban_id == NULL) {
                        continue;
                    }

                    if ($item->jawaban_id === $item->soal->kunci_jawaban) {
                        $jml_benar += 1;
                    } else {
                        $jml_salah += 1;
                    }
                }

                $ujianUser->jml_benar = $jml_benar;
                $ujianUser->jml_kosong = $ujianUser->jawabanPeserta->where('jawaban_id', NULL)->count();
                $ujianUser->jml_salah = $jml_salah;
            }

            $ujianUser->update();
        }

        return response()->json('Data berhasil disimpan', 200);
    }

    public function nilai($id)
    {
        $ujian = Ujian::with(['ujianUser' => function($query) {
                            $query->where('is_first', 1)->where('user_id', auth()->user()->id)->first();
                        }, 'ujianUser.jawabanPeserta', 'ujianUser.jawabanPeserta.soal.jawaban'])
                    ->findOrFail($id);
        if ($ujian->ujianUser[0]->status != '2' || $ujian->ujianUser[0]->user_id != auth()->user()->id) {
            abort(403, 'ERROR');
        }

        $ujianUser = UjianUser::with('user.usersDetail')
                        ->where('ujian_id', $id)
                        ->where('is_first', 1)
                        ->orderBy('nilai', 'desc')
                        ->get();
        $totalRank = $ujianUser->count();
        $rankUser = $ujianUser->where('user_id', auth()->user()->id);
        $rank = $rankUser->keys()->first() + 1;

        $userFormasi = $ujianUser->where('user.usersDetail.penempatan', auth()->user()->usersDetail->penempatan)->values();
        $totalRankFormasi = $userFormasi->count();
        $rankUserFormasi = $userFormasi->where('user_id', auth()->user()->id);
        $rankUserFormasi = $rankUserFormasi->keys()->first() + 1;

        return view('views_user.nilai.index', compact('ujian', 'totalRank', 'rank', 'totalRankFormasi', 'rankUserFormasi'));
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
