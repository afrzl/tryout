<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index() {
        if (auth()->check()) {
            $paketUjian = Pembelian::where('status', 'Sukses')->where('user_id', auth()->id())->get()->pluck('paket_id');
            $pengumumans = Pengumuman::where('paket_id', null)
                        ->orWhereIn('paket_id', $paketUjian)
                        ->orderBy('created_at', 'desc')
                        ->get();
        } else {
            $pengumumans = Pengumuman::where('paket_id', null)
                            ->orderBy('created_at', 'desc')
                            ->get();
        }

        return view('views_user.pengumuman.index', compact('pengumumans'));
    }
}
