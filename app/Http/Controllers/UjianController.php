<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use App\Models\Ujian;
use App\Models\Jawaban;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Models\JawabanPeserta;

class UjianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!session('ujian')) {
            return redirect()->route('dashboard');
        }
        $pembelian = Pembelian::where('ujian_id', session('ujian'))
                    ->where('user_id', auth()->user()->id)
                    ->latest('updated_at')
                    ->first();

        if ($pembelian === null || $pembelian->status == 'Gagal') {
            abort(403, 'ERROR');
        }

        if ($pembelian->status_pengerjaan == 'Selesai') {
            return redirect()->route('ujian.selesai', $pembelian->id);
        }

        $id_pembelian = $pembelian->id;
        $soal = Soal::with(
                ['ujian', 'jawaban','jawabanPeserta' => function ($query) use ($id_pembelian) {
                    $query->where('jawaban_peserta.pembelian_id', $id_pembelian);
                }])
                ->where('ujian_id', session('ujian'))
                ->paginate(1);
        return view('views_user.ujian.index', compact('soal', 'pembelian'));
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
            'pembelian_id' => 'required',
            'soal_id' => 'required',
            'key' => 'required',
            'jawaban_id' => 'required',
        ]);

        $jawaban_peserta = JawabanPeserta::where('pembelian_id', $request->pembelian_id)->where('soal_id', $request->soal_id)->first();
        if ($jawaban_peserta === null) {
            $store = new JawabanPeserta();
            $store->pembelian_id = $request->pembelian_id;
            $store->soal_id = $request->soal_id;
            $store->jawaban_id = $request->jawaban_id[$request->key];
            $store->save();
        } else {
            $jawaban_peserta->jawaban_id = $request->jawaban_id[$request->key];
            $jawaban_peserta->update();
        }

        return response()->json('Data berhasil disimpan', 200);
    }

    public function storeRagu(Request $request, $id)
    {
        $jawaban = JawabanPeserta::find($id);
        $jawaban->ragu_ragu = $jawaban->ragu_ragu == 1 ? 0 : 1;
        $jawaban->update();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pembelian = Pembelian::with('ujian')
                    ->where('ujian_id', $id)
                    ->where('user_id', auth()->user()->id)
                    ->latest('updated_at')
                    ->first();

        if ($pembelian === null || $pembelian->status == 'Gagal') {
            abort(403, 'ERROR');
        }

        session(['ujian' => $id]);
        return view('views_user.ujian.show', compact('pembelian'));
    }

    public function mulaiUjian($id)
    {
        $pembelian = Pembelian::findOrFail($id);

        if ($pembelian->status_pengerjaan == 'Selesai') {
            return redirect()->route('ujian.nilai', $pembelian->id);
        }

        if ($pembelian->status_pengerjaan != 'Masih dikerjakan') {
            $pembelian->status_pengerjaan = 'Masih dikerjakan';
            $pembelian->waktu_mulai_pengerjaan = date('Y-m-d H:i:s');
            $pembelian->update();
        }

        return redirect()->route('ujian.index');
    }

    public function selesaiUjian($id)
    {
        $pembelian = Pembelian::findOrFail($id);

        if ($pembelian->status_pengerjaan == 'Masih dikerjakan') {
            $pembelian->status_pengerjaan = 'Selesai';
            $pembelian->update();
        }

        return response()->json('Data berhasil disimpan', 200);
    }

    public function nilai($id)
    {
        $benar = 0;
        $pembelian = Pembelian::with('ujian', 'jawabanPeserta.soal')
                    ->findOrFail($id);
        foreach ($pembelian->jawabanPeserta as $key => $jawabanPeserta) {
            if ($jawabanPeserta->jawaban_id == $jawabanPeserta->soal->id_kunci_jawaban) {
                $benar++;
            }
        }
        return view('views_user.nilai.index', compact('pembelian', 'benar'));
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
