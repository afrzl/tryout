<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Wilayah;
use App\Models\Pembelian;
use Illuminate\Http\Request;

class HimadaController extends Controller
{
    public function dashboard()
    {
        $pembelian = Pembelian::where('voucher_id', auth()->user()->voucher->id)->where('status', 'Sukses')->count();
        return view('views_himada.dashboard', compact('pembelian'));
    }

    public function peserta() {
        $kabupaten = Wilayah::whereRaw('LEFT(kode,  2) = ' . auth()->user()->usersDetail->provinsi)
                            ->whereRaw('LENGTH(kode) = 5')
                            ->get()
                            ->pluck('nama');
        $pembelian = Pembelian::where('voucher_id', auth()->user()->voucher->id)->where('status', 'Sukses')->count();
        return view('views_himada.peserta', compact('pembelian', 'kabupaten'));
    }

    public function dataPeserta(Request $request) {
        $pembelians = Pembelian::with('user')
                        ->with('voucher')
                        ->where('paket_id', 'd5f57505-fb5a-4f59-a301-3722ef581844')
                        ->where('voucher_id', auth()->user()->voucher->id)
                        ->orderBy('status', 'desc')
                        ->orderBy('created_at', 'desc');

        return datatables()
            ->eloquent($pembelians)
            ->addIndexColumn()
            ->addColumn('email', function ($pembelians)
            {
                return '<a href="javascript:void(0);" onclick="detailForm(`' . route('admin.pembelian.show', $pembelians->id) . '`)">' . $pembelians->user->email;
            })
            ->addColumn('nama', function ($pembelians)
            {
                return $pembelians->user->name;
            })
            ->addColumn('created_at', function ($pembelians)
            {
                return Carbon::parse($pembelians->created_at)->isoFormat('D MMMM Y HH:mm:ss');
            })
            ->addColumn('alamat', function ($pembelians)
            {
                if ($pembelians->user->usersDetail->kecamatan) {
                    return Wilayah::find($pembelians->user->usersDetail->kecamatan)->nama . ', ' . Wilayah::find($pembelians->user->usersDetail->kabupaten)->nama . ', ' . Wilayah::find($pembelians->user->usersDetail->provinsi)->nama;
                }
                return '-';
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
            ->rawColumns(['aksi', 'email', 'tanggal', 'voucher', 'status'])
            ->make(true);
    }
}
