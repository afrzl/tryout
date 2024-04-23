<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Voucher;
use App\Models\Wilayah;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TonasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::withCount(['pembelian' => function ($query) {
            return $query->where('status', 'Sukses');
        }])->where('himada_id', '!=', null)->get();
        $data[0] = $vouchers->pluck('user.name');
        $data[1] = $vouchers->pluck('pembelian_count');
        return view('admin.tonas.index', compact('vouchers', 'data'));
    }

    public function data()
    {
        $pembelian = Pembelian::with('user', 'user.usersDetail', 'voucher', 'voucher.user')
                ->where('paket_id', 'd5f57505-fb5a-4f59-a301-3722ef581844')
                ->where('status', 'Sukses')
                ->orderBy('created_at', 'asc');

        return datatables()
            ->eloquent($pembelian)
            ->addIndexColumn()
            ->addColumn('name', fn($pembelian) => $pembelian->user->name)
            ->addColumn('email', fn($pembelian) => $pembelian->user->email)
            ->addColumn('asal', function ($pembelian) {
                $kecamatan = Wilayah::where('kode', $pembelian->user->usersDetail->kecamatan)->get()->first()->nama;
                $kabupaten = Wilayah::where('kode', $pembelian->user->usersDetail->kabupaten)->get()->first()->nama;
                $provinsi = Wilayah::where('kode', $pembelian->user->usersDetail->provinsi)->get()->first()->nama;

                return $kecamatan . ', ' . $kabupaten . ', ' . $provinsi;
            })
            ->addColumn('referal', function ($pembelian) {
                if ($pembelian->voucher) {
                    return '<badge class="badge badge-success">' . $pembelian->voucher->user->name . '</badge>';
                } else {
                    return '-';
                }
            })
            ->rawColumns(['referal'])
            ->make(true);
    }
}
