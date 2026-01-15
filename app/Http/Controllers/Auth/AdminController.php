<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\NoHpRequest;
use App\Models\User;
use App\Models\PerkembanganBayi;
use App\Models\Bayi;
use App\Models\Mpasi;
use App\Models\Artikel;
use App\Models\KategoriArtikel;
use App\Models\KategoriMpasi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class AdminController extends Controller
{
    //------------------------------------------------------------------
    // DASHBOARD
    //------------------------------------------------------------------
    public function dashboard()
    {
        $totalUser = User::count();
        $totalResep = Mpasi::count();
        $totalArtikel = Artikel::count();
        $latestUsers = User::latest()->take(5)->get();
        $latestArticles = Artikel::latest()->take(5)->get();
        
          // Hitung jumlah user per bulan untuk tahun ini
    $userPerMonth = [];
    for ($month = 1; $month <= 12; $month++) {
        $userPerMonth[$month] = User::whereYear('created_at', now()->year)
                                     ->whereMonth('created_at', $month)
                                     ->count();
    }
        $maleCount = Bayi::where('jenis_kelamin', 'Laki-laki')->count();
        $femaleCount = Bayi::where('jenis_kelamin', 'Perempuan')->count();       
         return view('admin.dashboard', compact(
            'totalUser', 'totalResep', 'totalArtikel', 
            'latestUsers', 'latestArticles', 'userPerMonth',
            'maleCount', 'femaleCount'
));
    }
//------------------------------------------------------------------
// KELOLA USER
//------------------------------------------------------------------
public function kelolaUser(Request $request)
{
    $search = $request->input('search');
    $role = $request->input('role');

    $query = User::with('bayis')
        ->when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->when($role, function ($query, $role) {
            return $query->where('role', $role);
        })
        ->orderBy('role');
        
    // Pagination dengan query string tetap terbawa
    $users = $query->paginate(10)->withQueryString();
    // format data bayi supaya angka tampil rapi
    foreach ($users as $user) {
        foreach ($user->bayis as $bayi) {
            $bayi->berat = !is_null($bayi->berat)
                ? ((fmod($bayi->berat, 1) == 0)
                    ? number_format($bayi->berat, 0, '.', '')
                    : number_format($bayi->berat, 1, '.', ''))
                : '-';

            $bayi->tinggi = !is_null($bayi->tinggi)
                ? ((fmod($bayi->tinggi, 1) == 0)
                    ? number_format($bayi->tinggi, 0, '.', '')
                    : number_format($bayi->tinggi, 1, '.', ''))
                : '-';

            $bayi->volume_asi = !is_null($bayi->volume_asi)
                ? ((fmod($bayi->volume_asi, 1) == 0)
                    ? number_format($bayi->volume_asi, 0, '.', '')
                    : number_format($bayi->volume_asi, 1, '.', ''))
                : '-';

            $bayi->kalori_per_porsi = !is_null($bayi->kalori_per_porsi)
                ? ((fmod($bayi->kalori_per_porsi, 1) == 0)
                    ? number_format($bayi->kalori_per_porsi, 0, '.', '')
                    : number_format($bayi->kalori_per_porsi, 1, '.', ''))
                : '-';
        }
    }
    return view('admin.kelolauser', compact('users', 'search', 'role'));
}

public function hapusUser($id)
{
    $user = User::findOrFail($id);
 $user->delete();
    return back()->with('success', 'User berhasil dihapus');
}

public function editUser($id)
{
    $user = User::with('bayis')->findOrFail($id);
    return view('admin.edituser', compact('user'));
}

public function updatePassword(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'password' => 'required|string|min:8|confirmed',
    ], [
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 8 karakter',
        'password.confirmed' => 'Password tidak cocok',
    ]);

    $user->update([
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('admin.users')->with('success', 'Password berhasil diperbarui');
}

public function updateUser(NoHpRequest $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'username' => [
            'required',
            'string',
            'min:4',
            'max:25',
            'regex:/^[a-zA-Z0-9_]+$/',
            'unique:users,username,' . $id,
        ],

        'nama' => [
            'required',
            'string',
            'min:3',
            'max:100',
            'regex:/^[a-zA-Z\s]+$/',
        ],

        'email' => 'required|email|unique:users,email,' . $id,

        'no_hp' => [
            'required',
            'regex:/^[0-9]+$/',
            'min:9',
            'max:205',
        ],

        'alamat' => [
            'required',
            'string',
            'min:5',
            'max:200',
        ],
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
    ]);

    $user->update([
        'username' => $request->username,
        'nama'     => $request->nama,
        'email'    => $request->email,
        'no_hp'    => $request->no_hp,
        'alamat'   => $request->alamat,
    ]);

    return redirect()->route('admin.users')->with('success', 'User berhasil diperbarui');
}

public function storeUser(NoHpRequest $request)
{
    $validatedData = $request->validate([

        'nama' => [
            'required',
            'string',
            'min:3',
            'max:100',
            'regex:/^[a-zA-Z\s]+$/',
        ],

        'username' => [
            'required',
            'string',
            'min:4',
            'max:25',
            'regex:/^[a-zA-Z0-9_]+$/',
            'unique:users,username',
        ],

        'email' => 'required|email|max:200|unique:users,email',

        'password' => [
            'required',
            'string',
            'min:8',
        ],

        'role' => 'required|in:user,admin',

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

        // Password
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 8 karakter.',

        // No HP
        'no_hp.required' => 'Nomor HP wajib diisi.',
        'no_hp.regex' => 'Nomor HP hanya boleh berisi angka.',
        'no_hp.min' => 'Nomor HP minimal 9 digit.',
        'no_hp.max' => 'Nomor HP maksimal 20 digit.',

        // Alamat
        'alamat.required' => 'Alamat wajib diisi.',
        'alamat.min' => 'Alamat minimal 5 karakter.',
        'alamat.max' => 'Alamat maksimal 200 karakter.',

    ]);

    User::create([
        'nama' => $validatedData['nama'],
        'username' => $validatedData['username'],
        'email' => $validatedData['email'],
        'password' => Hash::make($validatedData['password']),
        'role' => $validatedData['role'],
        'no_hp' => $validatedData['no_hp'],
        'alamat' => $validatedData['alamat'],
    ]);

    return redirect()->route('admin.users')
        ->with('success', 'User berhasil ditambahkan!');
}


   //------------------------------------------------------------------
    // KELOLA RESEP MPASI
    //------------------------------------------------------------------
    public function tambahMpasi()
    {
        return view('admin.tambah_mpasi');
    }

public function simpanMpasi(Request $request)
{
   $request->validate([
        'nama_menu'   => 'required|string|max:150',
        'kategori_id' => 'required|exists:kategori_mpasi,id',
        'porsi'       => 'required|integer|min:1|max:30',
        'min_umur'    => 'required|numeric|lte:max_umur',
        'max_umur'    => 'required|numeric| gte:min_umur',
        'energi'      => 'required|numeric|gte:30|lte:500',      // kkal
    'karbohidrat' => 'nullable|numeric|gte:0|lte:100',     // gram
    'protein'     => 'nullable|numeric|gte:0|lte:50',      // gram
    'lemak'       => 'nullable|numeric|gte:0|lte:50',      // gram
    'zat_besi'    => 'nullable|numeric|gte:0|lte:30',      // mg
        'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'bahan'       => 'required|array|min:1',
        'bahan.*'     => 'required|string',
        'takaran'     => 'required|array|min:1',
        'takaran.*'   => 'required|string',
        'langkah'     => 'required|array|min:1',
        'langkah.*'   => 'required|string', 
         ], [
    'nama_menu.required'=> 'Nama menu harus diisi',
     'nama_menu.max' => 'nama menu tidak boleh lebih 150.',
    'min_umur.lte' => 'Minimal umur harus lebih kecil atau sama dengan maksimal umur.',
    'max_umur.gte' => 'Maksimal umur harus lebih besar atau sama dengan minimal umur.',
    'bahan.required' => 'Minimal isi satu bahan.',
'bahan.*.required' => 'Bahan tidak boleh kosong.',

'takaran.required' => 'Minimal isi satu takaran.',
'takaran.*.required' => 'Takaran tidak boleh kosong.',

'langkah.required' => 'Minimal isi satu langkah pembuatan.',
'langkah.*.required' => 'Langkah tidak boleh kosong.',
    'energi.required' => 'Energi wajib diisi.',
    'energi.gte' => 'Energi minimal adalah 30 kkal.',
    'energi.lte' => 'Energi maksimal adalah 500 kkal.',
    'karbohidrat.gte' => 'Karbohidrat minimal adalah 0 gram.',
    'karbohidrat.lte' => 'Karbohidrat maksimal adalah 100 gram.',
    'protein.gte' => 'Protein minimal adalah 0 gram.',
    'protein.lte' => 'Protein maksimal adalah 50 gram.',
    'lemak.gte' => 'Lemak minimal adalah 0 gram.',
    'lemak.lte' => 'Lemak maksimal adalah 50 gram.',
    'zat_besi.gte' => 'Zat Besi minimal adalah 0 mg.',
    'zat_besi.lte' => 'Zat Besi maksimal adalah 30 mg.',
    'min_umur.required' => 'Min Umur wajib diisi.',
    'max_umur.required' => 'Max Umur wajib diisi.',
    'porsi.required' => 'Porsi wajib diisi.',
    'porsi.min' => 'Porsi minimal diisi 1 porsi.',
    'porsi.max' => 'Porsi maksimal diisi 30 porsi.',
    'kategori_id.required' => 'Kategori wajib dipilih.',
    'gambar.image' => 'File harus berupa gambar (jpg, jpeg, png).',
    'gambar.mimes' => 'Gambar harus berformat jpg, jpeg, atau png.',
    'gambar.max' => 'Ukuran gambar maksimal 2MB.',

       ]);

    $data = $request->only([
        'nama_menu', 'kategori_id', 'porsi', 'min_umur', 'max_umur',
        'energi', 'karbohidrat', 'protein', 'lemak', 'zat_besi'
    ]);
    $data['user_id'] = auth()->id();

 // =============== START: PERUBAHAN UNTUK MENGATASI NAMA FILE PANJANG =================
if ($request->hasFile('gambar')) {

    $file = $request->file('gambar');

    $namaFile = Str::uuid() . '.' . $file->getClientOriginalExtension();

    $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/gambar_mpasi';

    if (!file_exists($uploadPath)) {

        mkdir($uploadPath, 0755, true);

    }

    $file->move($uploadPath, $namaFile);

    // Simpan path relatif, bukan hanya nama file

    $data['gambar'] = 'uploads/gambar_mpasi/' . $namaFile;

}


    $mpasi = Mpasi::create($data);

    // Simpan bahan
foreach ($request->bahan ?? [] as $i => $bahan) {
    $mpasi->bahans()->create([
        'bahan'   => $bahan,
        'takaran' => $request->takaran[$i] ?? '',
    ]);
}
    //simpan langkah
foreach ($request->langkah ?? [] as $i => $langkah) {
    $mpasi->langkahs()->create([
        'urutan'  => $i + 1,
        'langkah' => $langkah,
    ]);
}

    return redirect()->route('admin.mpasi')->with('success', 'Data MPASI berhasil ditambahkan!');
}

    public function editMpasi($id)
    {
        $mpasi = Mpasi::findOrFail($id);
         $categories = KategoriMpasi::all();
        return view('admin.editmpasi', compact('mpasi', 'categories'));
    }

public function updateMpasi(Request $request, $id)
{
    $mpasi = Mpasi::findOrFail($id);

   $request->validate([
        'nama_menu'   => 'required|string|max:150',
        'kategori_id' => 'required|exists:kategori_mpasi,id',
        'porsi'       => 'required|integer|min:1|max:30',
        'min_umur'    => 'required|numeric|lte:max_umur',
        'max_umur'    => 'required|numeric| gte:min_umur',
        'energi'      => 'required|numeric|gte:30|lte:500',      // kkal
    'karbohidrat' => 'nullable|numeric|gte:0|lte:100',     // gram
    'protein'     => 'nullable|numeric|gte:0|lte:50',      // gram
    'lemak'       => 'nullable|numeric|gte:0|lte:50',      // gram
    'zat_besi'    => 'nullable|numeric|gte:0|lte:30',      // mg
        'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'bahan'       => 'required|array|min:1',
        'bahan.*'     => 'required|string',
        'takaran'     => 'required|array|min:1',
        'takaran.*'   => 'required|string',
        'langkah'     => 'required|array|min:1',
        'langkah.*'   => 'required|string', 
         ], [
    'nama_menu.required' => 'Nama menu harus diisi.',
    'nama_menu.max' => 'nama menu tidak boleh lebih 150.',
    'min_umur.lte' => 'Minimal umur harus lebih kecil atau sama dengan maksimal umur.',
    'max_umur.gte' => 'Maksimal umur harus lebih besar atau sama dengan minimal umur.',
'bahan.required' => 'Minimal isi satu bahan.',
'bahan.*.required' => 'Bahan tidak boleh kosong.',

'takaran.required' => 'Minimal isi satu takaran.',
'takaran.*.required' => 'Takaran tidak boleh kosong.',

'langkah.required' => 'Minimal isi satu langkah pembuatan.',
'langkah.*.required' => 'Langkah tidak boleh kosong.',
    'energi.required' => 'Energi wajib diisi.',
    'energi.gte' => 'Energi minimal adalah 30 kkal.',
    'energi.lte' => 'Energi maksimal adalah 500 kkal.',
    'karbohidrat.gte' => 'Karbohidrat minimal adalah 0 gram.',
    'karbohidrat.lte' => 'Karbohidrat maksimal adalah 100 gram.',
    'protein.gte' => 'Protein minimal adalah 0 gram.',
    'protein.lte' => 'Protein maksimal adalah 50 gram.',
    'lemak.gte' => 'Lemak minimal adalah 0 gram.',
    'lemak.lte' => 'Lemak maksimal adalah 50 gram.',
    'zat_besi.gte' => 'Zat Besi minimal adalah 0 mg.',
    'zat_besi.lte' => 'Zat Besi maksimal adalah 30 mg.',
    'min_umur.required' => 'Min Umur wajib diisi.',
    'max_umur.required' => 'Max Umur wajib diisi.',
    'porsi.required' => 'Porsi wajib diisi.',
    'porsi.min' => 'Porsi minimal diisi 1 porsi.',
    'porsi.max' => 'Porsi maksimal diisi 30 porsi.',
    'kategori_id.required' => 'Kategori wajib dipilih.',
    'gambar.image' => 'File harus berupa gambar (jpg, jpeg, png).',
    'gambar.mimes' => 'Gambar harus berformat jpg, jpeg, atau png.',
    'gambar.max' => 'Ukuran gambar maksimal 2MB.',
     ]);
     
    $data = $request->only([
        'nama_menu', 'kategori_id', 'porsi', 'min_umur', 'max_umur',
        'energi', 'karbohidrat', 'protein', 'lemak', 'zat_besi'
    ]);

 // Hapus gambar lama jika diganti
    if ($request->hasFile('gambar')) {
        $oldPath = public_path('uploads/gambar_mpasi/'.$mpasi->gambar);
        if ($mpasi->gambar && file_exists($oldPath)) {
            unlink($oldPath);
        }

        $file     = $request->file('gambar');
        $namaFile = Str::uuid().'.'.$file->getClientOriginalExtension();
       $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/gambar_mpasi';
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath,0755,true);
        }
        $file->move($uploadPath, $namaFile);
        $data['gambar'] = 'uploads/gambar_mpasi/' . $namaFile;
    }
    $mpasi->update($data);

    // Hapus bahan & langkah lama
    $mpasi->bahans()->delete();
    $mpasi->langkahs()->delete();

    // Insert ulang bahan
foreach ($request->bahan ?? [] as $i => $bahan) {
    $mpasi->bahans()->create([
        'bahan'   => $bahan,
        'takaran' => $request->takaran[$i] ?? '',
    ]);
}
//insert ulang langkah
foreach ($request->langkah ?? [] as $i => $langkah) {
    $mpasi->langkahs()->create([
        'urutan' => $i + 1,
        'langkah' => $langkah,
    ]);
}


    return redirect()->route('admin.mpasi')->with('success', 'Data MPASI berhasil diperbarui!');
}

    public function hapusMpasi($id)
    {
        $mpasi = Mpasi::findOrFail($id);
        if ($mpasi->gambar) {
    $path = public_path('uploads/gambar_mpasi/'.$mpasi->gambar);
    if (file_exists($path)) unlink($path);
}

        $mpasi->delete();
        return back()->with('success', 'Data MPASI berhasil dihapus');
    }

public function kelolaMpasi(Request $request)
{
    $search   = $request->input('search');
    $kategori = $request->input('kategori');

    // Ambil semua kategori
    $categories = KategoriMpasi::all();

    // Build query dengan filter search dan kategori
    $query = Mpasi::with(['kategori', 'bahans', 'langkahs'])
        ->when($search, function ($query, $search) {
            return $query->where('nama_menu', 'like', "%{$search}%");
        })
        ->when($kategori, function ($query, $kategori) {
            return $query->where('kategori_id', $kategori);
        });

    // Gunakan query yang sudah difilter
    $mpasis = $query->paginate(10);

    return view('admin.kelolampasi', compact('mpasis', 'categories', 'search', 'kategori'));
}


   //------------------------------------------------------------------
    // KELOLA ARTIKEL
    //------------------------------------------------------------------

public function kelolaArtikel(Request $request)
{
    $search = $request->input('search');
    $kategori = $request->input('kategori');
    $categories = KategoriArtikel::all();

    $query = Artikel::with('user', 'kategori')->latest();

    if ($search) {
        $query->where('judul', 'like', "%{$search}%");
    }

    if ($kategori) {
        $query->where('kategori_id', $kategori); 
    }

    $artikels = $query->paginate(10);

    return view('admin.kelolaartikel', compact('artikels', 'categories', 'search', 'kategori'));
}

public function show($id)
{
    $artikel = Artikel::with('kategori')->findOrFail($id);

    return response()->json([
        'id' => $artikel->id,
        'judul' => $artikel->judul,
        'isi' => $artikel->isi,
        'gambar' => $artikel->gambar ? asset($artikel->gambar) : null,
        'kategori' => $artikel->kategori ? $artikel->kategori->nama_kategori : '-',
        'estimasi' => $artikel->estimasi_waktu ?? '— menit'
    ]);
}

public function tambahArtikel()
{
    $categories = KategoriArtikel::all();
    return view('admin.tambah_artikel', compact('categories'));
}

public function simpanArtikel(Request $request)
{
    $request->validate([
        'judul' => 'required|string|max:150',
        'isi' => 'required|string',
        'kategori_id' => 'required|exists:kategori_artikel,id',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'sumber' => 'required|string|max:250',
    ], [
        'judul.required' => 'Judul wajib diisi.',
        'isi.required' => 'Isi artikel wajib diisi.',
        'kategori_id.required' => 'Kategori wajib dipilih.',
        'sumber.required' => 'Sumber wajib diisi.',
        'gambar.image' => 'File harus berupa gambar (jpg, jpeg, png, gif).',
        'gambar.mimes' => 'Gambar harus berformat jpg, jpeg, png, atau gif.',
        'gambar.max' => 'Ukuran gambar maksimal 2MB.',
    ]);

    try {
        $data = [
            'user_id' => auth()->id(),
            'judul' => $request->judul,
            'isi' => $request->isi,
            'kategori_id' => $request->kategori_id,
            'tanggal_post' => now(),
            'sumber' => $request->sumber
        ];

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $namaFile = Str::uuid() . '.' . $gambar->getClientOriginalExtension();
            $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/gambar_artikel';
            if (!file_exists($uploadPath)) mkdir($uploadPath, 0755, true);
            $gambar->move($uploadPath, $namaFile);
            $data['gambar'] = 'uploads/gambar_artikel/' . $namaFile;
        }

        Artikel::create($data);

        return redirect()->route('admin.kelolaartikel')
            ->with('success', 'Artikel berhasil ditambahkan!');
    } catch (\Exception $e) {
        return redirect()->route('admin.kelolaartikel')
            ->with('error', 'Artikel gagal ditambahkan. Silakan coba lagi.');
    }
}

public function editArtikel($id)
{
    $artikel = Artikel::findOrFail($id);
    $categories = KategoriArtikel::all();
    return view('admin.editartikel', compact('artikel', 'categories'));
}

public function updateArtikel(Request $request, $id)
{
    $artikel = Artikel::findOrFail($id);

    // ================================
    // VALIDASI (fix khusus untuk Trix)
    // ================================
    $request->validate([
        'judul' => 'required|string|max:150',
        'isi' => ['required', function ($attribute, $value, $fail) {
            // Buang semua HTML Trix
            $plain = trim(strip_tags($value));

            // Jika hasilnya kosong → dianggap tidak mengisi konten
            if ($plain === '') {
                $fail('Konten artikel wajib diisi.');
            }
        }],
        'kategori_id' => 'required|exists:kategori_artikel,id',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'sumber' => 'required|string|max:250',
    ], [
        'judul.max' => 'Judul tidak boleh lebih dari 150.',
        'judul.required' => 'Judul wajib diisi.',
        'kategori_id.required' => 'Kategori wajib dipilih.',
        'sumber.required' => 'Sumber wajib diisi.',
        'sumber.max' => 'sumber tidak boleh lebih dari 150.',
        'gambar.image' => 'File harus berupa gambar (jpg, jpeg, png, gif).',
        'gambar.mimes' => 'Gambar harus berformat jpg, jpeg, png, atau gif.',
        'gambar.max' => 'Ukuran gambar maksimal 2MB.',
    ]);

    // ================================
    // SIMPAN DATA
    // ================================
    $data = [
        'judul' => $request->judul,
        'isi' => $request->isi,
        'kategori_id' => $request->kategori_id,
        'sumber' => $request->sumber
    ];

    // ================================
    // UPLOAD GAMBAR JIKA ADA
    // ================================
    if ($request->hasFile('gambar')) {

        // Hapus gambar lama
        if ($artikel->gambar && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$artikel->gambar)) {
            unlink($_SERVER['DOCUMENT_ROOT'].'/'.$artikel->gambar);
        }

        $gambar = $request->file('gambar');
        $namaFile = Str::uuid() . '.' . $gambar->getClientOriginalExtension();
        $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/gambar_artikel';

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $gambar->move($uploadPath, $namaFile);
        $data['gambar'] = 'uploads/gambar_artikel/' . $namaFile;
    }

    // Update artikel
    $artikel->update($data);

    // ================================
    // REDIRECT SUKSES
    // ================================
    return redirect()->route('admin.kelolaartikel')->with('success', 'Artikel berhasil diperbarui!');
}


public function hapusArtikel($id)
{
    $artikel = Artikel::findOrFail($id);

    // Hapus gambar utama
    if ($artikel->gambar && file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$artikel->gambar)) {
        unlink($_SERVER['DOCUMENT_ROOT'].'/'.$artikel->gambar);
    }

    // Hapus gambar di isi artikel (<img src="...">)
    preg_match_all('/<img[^>]+src="([^">]+)"/', $artikel->isi, $matches);
    if (!empty($matches[1])) {
        foreach ($matches[1] as $src) {
            $path = str_replace(asset('/'), '', $src);
            if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$path)) {
                unlink($_SERVER['DOCUMENT_ROOT'].'/'.$path);
            }
        }
    }

    $artikel->delete();
    return back()->with('success', 'Artikel berhasil dihapus.');
}

public function upload(Request $request)
{
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $namaFile = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/gambar_artikel';
        if (!file_exists($uploadPath)) mkdir($uploadPath, 0755, true);
        $file->move($uploadPath, $namaFile);

        return response()->json([
            'url' => '/uploads/gambar_artikel/' . $namaFile
        ]);
    }
    return response()->json(['error' => 'Upload gagal'], 422);
}
}