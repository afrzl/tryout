<?php

namespace App\Http\Controllers\Admin;

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
        return view('admin.tonas.index');
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
            ->addColumn('no_hp', fn($pembelian) => $pembelian->user->usersDetail->no_hp)
            ->addColumn('asal', function ($pembelian) {
                return $pembelian->user->usersDetail->kabupaten;
            })
            ->addColumn('referal', function ($pembelian) {
                if ($pembelian->voucher) {
                    return $pembelian->voucher->user->name;
                } else {
                    return '-';
                }
            })
            ->rawColumns(['kelompok'])
            ->make(true);
    }
}
