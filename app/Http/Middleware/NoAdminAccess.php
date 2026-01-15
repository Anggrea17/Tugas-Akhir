<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class NoAdminAccess
{
    public function handle($request, Closure $next)
    {
        // Jika user login dan dia admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            // Larang akses ke halaman tertentu
            return redirect()->route('admin.dashboard')
            // Tambahkan pesan error yang dimana pesannya sesuai sama route yang tidak boleh diakses admin
                ->with('error', 'Halaman tersebut hanya untuk pengguna biasa.');
        }

        return $next($request);
    }
}
