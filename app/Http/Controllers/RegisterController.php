<?php

namespace App\Http\Controllers;
use App\Http\Requests\NoHpRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

public function store(Request $request)
{
    $request->validate([
                'username' => [
            'required',
            'string',
            'min:4',
            'max:25',
            'regex:/^[a-zA-Z0-9_]+$/',
            'unique:users,username,'
        ],

        'nama' => [
            'required',
            'string',
            'min:3',
            'max:100',
            'regex:/^[a-zA-Z\s]+$/',
        ],

        'email' => 'required|email|unique:users,email,',

        'no_hp' => [
            'required',
            'regex:/^[0-9]+$/',
            'min:9',
            'max:20',
        ],

        'alamat' => [
            'required',
            'string',
            'min:5',
            'max:200',
        ],
        'password' => 'required|min:8|confirmed',
    ], [
           // Username
        'username.required' => 'Username wajib diisi.',
        'username.min' => 'Username minimal 4 karakter.',
        'username.max' => 'Username maksimal 25 karakter.',
        'username.regex' => 'Username hanya boleh mengandung huruf, angka, dan underscore.',
        'username.unique' => 'Username sudah terdaftar.',

        // Nama
        'nama.required' => 'Nama wajib diisi.',
        'nama.min' => 'Nama minimal 3 karakter.',
        'nama.max' => 'Nama maksimal 100 karakter.',
        'nama.regex' => 'Nama hanya boleh mengandung huruf dan spasi.',

        // Email
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah terdaftar.',

        // No HP
        'no_hp.required' => 'Nomor HP wajib diisi.',
        'no_hp.regex' => 'Nomor HP hanya boleh berisi angka.',
        'no_hp.min' => 'Nomor HP minimal 9 digit.',
        'no_hp.max' => 'Nomor HP maksimal 20 digit.',

        // Alamat
        'alamat.required' => 'Alamat wajib diisi.',
        'alamat.min' => 'Alamat minimal 5 karakter.',
        'alamat.max' => 'Alamat maksimal 200 karakter.',

        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 8 karakter.',
        'password.confirmed' => 'Password tidak cocok.',
        
    ]);

    $user = User::create([
        'nama' => $request->nama,
        'username' => $request->username,
        'alamat' => $request->alamat,
        'no_hp' => $request->no_hp,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'user',
    ]);

    Auth::login($user);

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

}
