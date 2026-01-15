<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bayi;
use App\Models\PerkembanganBayi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


class ProfileController extends Controller
{
    // Tampilkan form edit profile
    public function edit()
    {
        $user = Auth::user();
        $bayis = $user->bayis;
          // Kalau ada old input (validasi gagal), pakai itu
    $oldBayis = old('bayi', $bayis->toArray());

    return view('editprofile', [
        'user' => $user,
        'bayis' => $bayis,
        'oldBayis' => $oldBayis 
    ]);
    }


    // =========================
    // Update data User
    // =========================
public function updateUser(Request $request)
{
    $user = Auth::user();

    // Normalisasi nomor HP: buang spasi, titik, tanda plus
    if ($request->no_hp) {
        $request->merge([
            'no_hp' => preg_replace('/[^0-9]/', '', $request->no_hp)
        ]);
    }

    $validator = \Validator::make($request->all(), [
        'nama'     => [
            'required',
            'string',
            'max:100',
            'regex:/^[a-zA-Z\s\'.-]+$/'
        ],

        'username' => [
            'nullable',
            'string',
            'max:25',
            'regex:/^[A-Za-z0-9_]+$/', // tidak boleh ada spasi
            'unique:users,username,' . $user->id
        ],

        'alamat'   => [
            'nullable',
            'string',
            'max:200',
            'regex:/^[A-Za-z0-9\s.,\/-]+$/'
        ],

        'no_hp'    => [
            'nullable',
            'regex:/^[0-9]{9,20}$/'
        ],
    ], [
        'nama.required' => 'Nama harus diisi.',
        'nama.regex' => 'Nama hanya boleh berisi huruf dan spasi.',

        'username.regex' => 'Username hanya boleh berisi huruf, angka, dan underscore tanpa spasi.',
        'username.unique' => 'Username sudah digunakan oleh user lain.',

        'alamat.regex' => 'Alamat hanya boleh berisi huruf, angka, titik, koma, garis miring, dan spasi.',

        'no_hp.regex' => 'Nomor HP harus berupa angka dan panjang 9–20 digit.',
    ]);

    if ($validator->fails()) {
        return redirect()
            ->route('profile.edit')
            ->withErrors($validator, 'user') 
            ->withInput()
            ->with('tab', 'user');
    }

    // Update
    $user->update($request->only('nama', 'username', 'alamat', 'no_hp'));

    return redirect()
        ->route('profile.edit')
        ->with('success_user', 'Data user berhasil diperbarui!')
        ->with('tab', 'user');
}

// =========================
// Update / Tambah Data Bayi
// =========================
public function updateBayi(Request $request)
{
    $user = Auth::user();

    if (!$request->has('bayi')) {
        return back()->with([
            'error_bayi' => 'Form bayi tidak boleh kosong!',
            'tab' => 'bayi'
        ]);
    }

    foreach ($request->input('bayi', []) as $index => $data) {

        $label = !empty($data['nama_bayi'])
            ? "Bayi: " . $data['nama_bayi']
            : "Bayi #" . ($index + 1);

        // =========================
        // Validasi
        // =========================
        $rules = [
            'nama_bayi'      => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/',
            'tanggal_lahir'  => 'required|date|before_or_equal:today',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'berat'          => 'nullable|numeric|min:2|max:15',
            'tinggi'         => 'nullable|numeric|min:40|max:100',
            'jenis_susu'     => 'nullable|in:ASI,Sufor,Mix',
            'volume_asi'     => 'nullable|numeric|min:200|max:1000',
            'kalori_per_porsi' => 'nullable|numeric|min:20|max:120',
            'jumlah_porsi_per_hari' => 'nullable|numeric|min:1|max:8',
        ];

        $messages = [
            'nama_bayi.required' => "Nama $label harus diisi.",
            'nama_bayi.regex' => "Nama $label hanya boleh mengandung huruf dan spasi.",
            'tanggal_lahir.required' => "Tanggal lahir $label harus diisi.",
            'tanggal_lahir.before_or_equal' => "Tanggal lahir $label tidak boleh di masa depan.",
            'jenis_kelamin.required' => "Jenis kelamin $label harus diisi.",
            'jenis_kelamin.in' => "Jenis kelamin $label tidak valid.",
        ];

        // ubah string kosong jadi null
        foreach ([
            'jenis_susu', 'berat', 'tinggi',
            'volume_asi', 'kalori_per_porsi', 'jumlah_porsi_per_hari'
        ] as $field) {
            if (empty($data[$field])) {
                $data[$field] = null;
            }
        }

        $validator = \Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'bayi')
                ->withInput()
                ->with(['tab' => 'bayi']);
        }

        // =========================
        // Update / Create Bayi
        // =========================
        if (isset($data['id'])) {
            $bayi = $user->bayis()->where('id', $data['id'])->first();
            if ($bayi) {
                $bayi->update($data);
            }
        } else {
            $bayi = $user->bayis()->create($data);
        }

        // =========================
        // SIMPAN PERKEMBANGAN (ANTI DUPLIKAT)
        // =========================
        if ($bayi) {

            $umurBulan = Carbon::parse($bayi->tanggal_lahir)
                ->diffInMonths(Carbon::now());

            // Catatan hari ini
            $perkembanganHariIni = $bayi->perkembanganBayi()
                ->whereDate('tanggal_catat', Carbon::today())
                ->first();

            // Catatan terakhir (tanpa peduli tanggal)
            $perkembanganTerakhir = $bayi->perkembanganBayi()
                ->latest('tanggal_catat')
                ->first();

            $beratBerubah = $perkembanganTerakhir
                ? $bayi->berat != $perkembanganTerakhir->berat
                : true;

            $tinggiBerubah = $perkembanganTerakhir
                ? $bayi->tinggi != $perkembanganTerakhir->tinggi
                : true;

            // ✅ Bayi baru → catatan awal
            if ($bayi->wasRecentlyCreated) {

                $bayi->perkembanganBayi()->create([
                    'berat' => $bayi->berat,
                    'tinggi' => $bayi->tinggi,
                    'umur_bulan' => $umurBulan,
                    'tanggal_catat' => Carbon::now(),
                ]);

            } else {

                if ($perkembanganHariIni) {
                    // ✅ Hari sama → update jika ada perubahan
                    if ($beratBerubah || $tinggiBerubah) {
                        $perkembanganHariIni->update([
                            'berat' => $bayi->berat,
                            'tinggi' => $bayi->tinggi,
                            'umur_bulan' => $umurBulan,
                        ]);
                    }
                } else {
                    // ✅ Hari beda → buat baru HANYA jika ada perubahan
                    if ($beratBerubah || $tinggiBerubah) {
                        $bayi->perkembanganBayi()->create([
                            'berat' => $bayi->berat,
                            'tinggi' => $bayi->tinggi,
                            'umur_bulan' => $umurBulan,
                            'tanggal_catat' => Carbon::now(),
                        ]);
                    }
                }
            }
        }
    }

    return redirect()
        ->route('profile.edit')
        ->with('success_bayi', 'Data bayi berhasil diperbarui!')
        ->with('tab', 'bayi');
}



// =========================
// Hapus Data Bayi
// =========================
public function destroyBayi($id)
{
    $user = Auth::user();
    $bayi = $user->bayis()->find($id);

    if (!$bayi) {
        return redirect()
            ->route('profile.edit')
            ->with('error_bayi', 'Data bayi tidak ditemukan!')
            ->with('tab', 'bayi');
    }

    $nama = $bayi->nama_bayi;

    // Hapus semua catatan perkembangan terkait
    $bayi->perkembanganBayi()->delete();

    // Hapus data bayi
    $bayi->delete();

    return redirect()
        ->route('profile.edit')
        ->with('success_bayi', "Data bayi {$nama} dan semua catatan perkembangannya berhasil dihapus!")
        ->with('tab', 'bayi');
}

    /**
     * Ubah password
     */
    public function updatePassword(Request $request)
    {
        $request->session()->flash('tab', 'password');

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'password.confirmed' => 'Password tidak cocok',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Kalau sukses
      return redirect()
    ->route('profile.edit')
    ->with('success_password', 'Password berhasil diperbarui!')
    ->with('tab', 'password');

      }
}
