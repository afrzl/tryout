<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Pembelian;
use App\Models\PaketUjian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.pembelian.index');
    }

    public function data(Request $request)
    {
        $pembelians = Pembelian::with('user')
                        ->with('voucher')
                        ->where('paket_id', $request->paket_ujian)
                        ->orderBy('created_at', 'desc');

        return datatables()
            ->eloquent($pembelians)
            ->addIndexColumn()
            ->addColumn('email', function ($pembelians)
            {
                return $pembelians->user->email;
            })
            ->addColumn('nama', function ($pembelians)
            {
                return $pembelians->user->name;
            })
            ->addColumn('created_at', function ($pembelians)
            {
                return Carbon::parse($pembelians->created_at)->isoFormat('D MMMM Y HH:mm:ss');
            })
            ->addColumn('voucher', function ($pembelians)
            {
                if ($pembelians->voucher) {
                    return '<span class="badge badge-primary">'. $pembelians->voucher->kode .'</span>';
                    return $pembelians->voucher->kode . "(". $pembelians->voucher->diskon .")";
                }
            })
            ->addColumn('harga', function ($pembelians)
            {
                return 'Rp' . number_format($pembelians->harga , 0 , ',' , '.' );
            })
            ->addColumn('status', function ($pembelians)
            {
                if ($pembelians->status == "Sukses") {
                    return '<span class="badge badge-success">Sukses</span>';
                }
                if ($pembelians->status == "Belum dibayar") {
                    return '<span class="badge badge-warning">Belum Bayar</span>';
                }
                if ($pembelians->status == "Gagal") {
                    return '<span class="badge badge-danger">Gagal</span>';
                }
            })
            ->rawColumns(['aksi', 'tanggal', 'voucher', 'status'])
            ->make(true);
    }

    public function dataPaket() {
        $data = PaketUjian::get();
        return response()->json($data, 200);
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
