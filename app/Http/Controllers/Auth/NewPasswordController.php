<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NewPasswordController extends Controller
{
    public function create(Request $request)
    {
        // Cek apakah token valid dan belum expired
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        // Jika token tidak ditemukan
        if (!$tokenData) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Link reset password tidak valid atau sudah digunakan.']);
        }

        // Verifikasi apakah token cocok (Laravel menyimpan token dalam bentuk hash)
        if (!Hash::check($request->token, $tokenData->token)) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Link reset password tidak valid atau sudah digunakan.']);
        }

        // Cek apakah token sudah expired berdasarkan waktu created_at
        $tokenAge = Carbon::parse($tokenData->created_at);
        $expiryMinutes = config('auth.passwords.users.expire', 60); // Default 60 menit
        
        if ($tokenAge->addMinutes($expiryMinutes)->isPast()) {
            // Hapus token yang sudah expired
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();
                
            return redirect()->route('login')
                ->withErrors(['email' => 'Link reset password sudah kadaluarsa. Silakan minta link baru.']);
        }

        return view('auth.reset-password', ['request' => $request]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                // Hapus semua token reset password untuk user ini
                // Agar link tidak bisa digunakan lagi setelah berhasil reset
                DB::table('password_reset_tokens')
                    ->where('email', $user->email)
                    ->delete();
            }
        );

        // Jika token invalid atau expired, redirect ke login
        if ($status === Password::INVALID_TOKEN) {
            return redirect()->route('login')
                ->with('error', 'Link reset password tidak valid atau sudah digunakan.');
        }

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password berhasil diubah! Silakan login dengan password baru Anda.')
            : back()->withErrors(['email' => [__($status)]]);
    }
}