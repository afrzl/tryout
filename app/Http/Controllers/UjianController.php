<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use App\Models\Ujian;
use App\Models\Pembelian;
use Illuminate\Http\Request;

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
        $pembelian = Pembelian::where('ujian_id', session('ujian'))->where('user_id', auth()->user()->id)->latest('updated_at')->first();

        if ($pembelian === null || $pembelian->status == 'Gagal') {
            abort(403, 'ERROR');
        }

        $ujian = Ujian::find(session('ujian'));
        $soal = Soal::with('jawaban')->where('ujian_id', $ujian->id)->paginate(1);
        return view('views_user.ujian.index', compact('ujian', 'soal'));
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
        $pembelian = Pembelian::where('ujian_id', $id)->where('user_id', auth()->user()->id)->latest('updated_at')->first();

        if ($pembelian === null || $pembelian->status == 'Gagal') {
            abort(403, 'ERROR');
        }

        session(['ujian' => $id]);
        return view('views_user.ujian.show', compact('ujian'));
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
