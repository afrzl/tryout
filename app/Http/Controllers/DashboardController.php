<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\Message;
use App\Models\Ujian;
use App\Models\Pembelian;
use App\Models\PaketUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            $pakets = PaketUjian::orderBy('created_at', 'asc')->get();
            return view('views_user.dashboard', compact('pakets'));
        } else {
            $pakets = PaketUjian::with(['pembelian' => function($query) {
                            $query->where('user_id', auth()->user()->id)->where('status', "Sukses");
                        }])->orderBy('created_at', 'asc')->get();
            // return $pakets;
            return view('views_user.dashboard', compact('pakets'));
        }
    }

    public function adminIndex()
    {
        if (!(auth()->user()->hasRole('admin') || auth()->user()->hasRole('panitia') || auth()->user()->hasRole('bendahara'))) {
            abort(403, 'Error!');
        }

        $data['paketUjian'] = PaketUjian::count();
        $data['ujian'] = Ujian::count();
        $data['ujianActive'] = Ujian::where('waktu_mulai', '<=', now())
                                ->where('waktu_akhir', '>=', now())
                                ->where('isPublished', 1)
                                ->count();
        $data['pembelian'] = Pembelian::where('status', 'Sukses')
                                ->where('jenis_pembayaran', '!=', 'Bundling')
                                ->count();
        $data['user'] = User::doesntHave('roles')->count();

        return view('admin.dashboard', compact('data'));
    }

    public function sendEmail(Request $request) {
        Mail::to('ukmbimbelstis@gmail.com')->send(new Message($request));

        return response()->json([
            'message' => 'Sukses'
        ], 200); // Status code here
    }
}
