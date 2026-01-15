<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Kalau role cocok → lanjut
        if ($user->role === $role) {
            return $next($request);
        }

        // Kalau role tidak cocok → redirect ke dashboard sesuai role
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($user->role === 'user') {
            return redirect('/user/dashboard');
        }

        // fallback kalau ada role lain nanti
        return redirect('/dashboard');
    }
}
