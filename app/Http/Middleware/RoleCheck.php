<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan pengguna sudah terautentikasi
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Ambil role pengguna yang terautentikasi
        $userRole = Auth::user()->role;

        // Cek apakah role pengguna ada dalam daftar role yang diizinkan
        if (!in_array($userRole, $roles)) {
            // Jika tidak, arahkan ke halaman dashboard dengan pesan error
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        // Lanjutkan jika role pengguna cocok
        return $next($request);
    }
}
