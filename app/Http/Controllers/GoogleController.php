<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle() {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback() {
        $user = Socialite::driver('google')->user();

        $findUser = User::where(function ($q) use ($user) {
                        $q->where('google_id', $user->getId())->orWhere('email', $user->getEmail());
                    })
                    ->first();

        if ($findUser) {
            Auth::login($findUser);
            return redirect()->route('dashboard');
        } else {
            $newUser = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'google_id' => $user->getId(),
                'email_verified_at' => Carbon::now(),
                'password' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Libero, eligendi.',
            ]);

            Auth::login($newUser);
            return redirect()->route('dashboard');
        }
    }

}
