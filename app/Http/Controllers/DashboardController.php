<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\Pembelian;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            $ujians = Ujian::where('isPublished', 1)
                        ->where('waktu_mulai', '<=', date("Y-m-d H:i:s"))
                        ->where('waktu_akhir', '>=', date("Y-m-d H:i:s"))
                        ->get();
            $history = [];
            return view('views_user.dashboard', compact('ujians', 'history'));
        } else {
            if (auth()->user()->hasVerifiedEmail()) {
                $ujians = Ujian::where('isPublished', 1)
                            ->where('waktu_mulai', '<=', date("Y-m-d H:i:s"))
                            ->where('waktu_akhir', '>=', date("Y-m-d H:i:s"))
                            ->get();
                $history = Pembelian::with('ujian')
                            ->where('user_id', auth()->user()->id)
                            ->where('status', 'Sukses')
                            ->get();
                return view('views_user.dashboard', compact('ujians', 'history'));
            } else {
                return redirect()->route('verification.notice');
            }
        }
    }

    public function adminIndex()
    {
        if (auth()->user()->hasRole('admin')) {
            return view('layouts.admin.app');
        }
        abort(403, 'Error!');
    }
}
