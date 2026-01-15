<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

      // Tentukan apakah login pakai email atau username
    $loginType = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    // Cek apakah user dengan email/username tersebut ada
    $userModel = \App\Models\User::where($loginType, $request->input('login'))->first();

    if (!$userModel) {
        return back()->withErrors([
            'login' => $loginType === 'email'
                ? 'Email tidak terdaftar.'
                : 'Username tidak terdaftar.',
        ])->withInput();
    }

    // Coba login pakai Auth::attempt
    $credentials = [
        $loginType => $request->input('login'),
        'password' => $request->input('password'),
    ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect berdasarkan role
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended('/admin/dashboard');
                case 'user':
                    return redirect()->intended('/landingpg'); 
                    Auth::logout();
                    return back()->withErrors([
                        'login' => 'Role tidak dikenali, hubungi admin.',
                    ]);
            }
        }

        // Kalau gagal login
        throw ValidationException::withMessages([
            'login' => ['Login gagal. Username/email atau password salah.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/landingpg'); // redirect ke landing page
    }
}
