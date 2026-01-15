<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Exception;

class GoogleController extends Controller
{
    // Redirect ke Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    private function generateUsernameFromEmail($email)
{
    $base = explode('@', $email)[0]; // ambil sebelum @
    $username = $base . '_' . rand(1000, 9999);

    // pastikan unik
    while (User::where('username', $username)->exists()) {
        $username = $base . '_' . rand(1000, 9999);
    }

    return $username;
}

    // Callback dari Google
public function callback()
{
    try {
        $googleUser = Socialite::driver('google')->user();

        // cek apakah email user sudah ada di database
        $user = User::where('email', $googleUser->email)->first();

        if (!$user) {
               $username = $this->generateUsernameFromEmail($googleUser->getEmail());
            // buat user baru
            $user = User::create([
                'nama'   => $googleUser->name,
                'email'  => $googleUser->email,
                'username' => $username,
                'password' => str()->random(12),
                'alamat' => null,
                'no_hp'  => null,
                'role'   => 'user',
            ]);

            \Log::info('User baru dibuat via Google', $user->toArray());
        }

        // login pakai guard web
        Auth::guard('web')->login($user, true);

        \Log::info('User berhasil login via Google', [
            'id' => $user->id,
            'email' => $user->email
        ]);

        // kalau profil belum lengkap → ke edit profil
        if (!$user->alamat || !$user->no_hp) {
            return redirect()->route('profile.edit')
                ->with('info', 'Lengkapi data profil kamu dulu ya.');
        }

        // kalau role admin → dashboard
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // default → landing page
        return redirect()->route('landingpg');

    } catch (\Exception $e) {
        \Log::error('Google login error', ['message' => $e->getMessage()]);
        return redirect('/login')->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
    }
}
}