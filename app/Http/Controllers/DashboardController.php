<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            return view('layouts.app');
        } else {
            $ujians = Ujian::where('isPublished', 1)->get();
            return view('views_user.dashboard', compact('ujians'));
        }
    }
}
