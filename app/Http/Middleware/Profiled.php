<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Profiled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = \App\Models\User::with(['usersDetail' => function($query) {
                        $query->where('no_hp', '!=', NULL)
                                ->where('provinsi', '!=', NULL)
                                ->where('kabupaten', '!=', NULL)
                                ->where('kecamatan', '!=', NULL)
                                ->where('asal_sekolah', '!=', NULL)
                                ->where('sumber_informasi', '!=', NULL)
                                ->where('prodi', '!=', NULL)
                                ->where('penempatan', '!=', NULL)
                                ->where('instagram', '!=', NULL);
                    }])
                ->where('id', auth()->user()->id)
                ->first();
        if ($user->usersDetail) {
            return $next($request);
        }
        return redirect()->route('profile.show')->with('message', 'Silahkan lengkapi profil terlebih dahulu.');
    }
}
