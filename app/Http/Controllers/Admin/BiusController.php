<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use Illuminate\Http\Request;

class BiusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.bius.index');
    }

    public function data()
    {
        $pembelian = Pembelian::with('user')
                ->where('paket_id', '0df8c9b0-d352-448b-9611-abadffc4f46d')
                ->orderBy('created_at', 'asc');

        return datatables()
            ->eloquent($pembelian)
            ->addIndexColumn()
            ->addColumn('name', fn($pembelian) => $pembelian->user->name)
            ->addColumn('email', fn($pembelian) => $pembelian->user->email)
            ->addColumn('kelompok', function ($pembelian) {
                return '
                    <select name="kelompok[]" onChange="storeKelompok('. $pembelian->id .')" id="kel-'. $pembelian->id .'" class="form-control input-kelompok">
                        <option value="">-- Pilih Nama Kelompok--</option>
                        <option value="Poisson" '. ($pembelian->nama_kelompok == "Poisson" ? "selected" : "") .'>Poisson</option>
                        <option value="Bernoulli" '. ($pembelian->nama_kelompok == "Bernoulli" ? "selected" : "") .'>Bernoulli</option>
                        <option value="Binomial" '. ($pembelian->nama_kelompok == "Binomial" ? "selected" : "") .'>Binomial</option>
                    </select>';
            })
            ->rawColumns(['kelompok'])
            ->make(true);
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
        $validator = $request->validate([
            'id' => 'required',
            'kelompok' => 'required',
        ]);

        $pembelian = Pembelian::findOrFail($request->id);
        $pembelian->nama_kelompok = $request->kelompok;
        $pembelian->update();

        return response()->json('Berhasil menambahkan kelompok', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembelian $pembelian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembelian $pembelian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembelian $pembelian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembelian $pembelian)
    {
        //
    }
}
