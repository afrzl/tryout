<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index() {
        $faqs = Faq::orderBy('created_at', 'desc')
                    ->get();

        return view('views_user.faq.index', compact('faqs'));
    }
}
