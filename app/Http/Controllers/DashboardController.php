<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\Pembelian;
use App\Models\PaketUjian;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            $pakets = PaketUjian::orderBy('waktu_mulai', 'asc')->get();
            return view('views_user.dashboard', compact('pakets'));
        } else {
            $pakets = PaketUjian::with(['pembelian' => function($query) {
                            $query->where('user_id', auth()->user()->id)->where('status', "Sukses");
                        }])->orderBy('waktu_mulai', 'asc')->get();
            // return $pakets;
            return view('views_user.dashboard', compact('pakets'));
        }
    }

    public function adminIndex()
    {
        if (auth()->user()->hasRole('admin')) {
            return view('admin.dashboard');
        }
        abort(403, 'Error!');
    }
}
