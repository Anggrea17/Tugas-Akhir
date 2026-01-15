<?php

namespace App\Http\Controllers;

use App\Models\Bayi;
use App\Models\User;
use App\Models\PerkembanganBayi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;



class BayiController extends Controller
{
    /**
     * Tampilkan daftar bayi untuk user tertentu.
     */
    public function index(User $user)
    {
        // Mengambil semua data bayi untuk user ini
        $bayis = $user->bayis()->latest()->get();
   // Format semua angka agar tampil rapi
    foreach ($bayis as $bayi) {
        $bayi->berat = $this->formatNumber($bayi->berat);
        $bayi->tinggi = $this->formatNumber($bayi->tinggi);
        $bayi->volume_asi = $this->formatNumber($bayi->volume_asi);
        $bayi->kalori_per_porsi = $this->formatNumber($bayi->kalori_per_porsi);
    }         
         
        return view('admin.kelolabayi', compact('user', 'bayis'));
    }
///menampilkan perkembangan bayi untuk admin
public function show(User $user, Bayi $bayi)
{
    // Admin boleh lihat semua bayi, jadi hapus pembatasan user_id

    // Ambil data perkembangan bayi
    $perkembanganBayi = $bayi->perkembanganBayi()
        ->orderBy('tanggal_catat', 'desc')
        ->get();

    // Format angka biar tidak ada .00
    foreach ($perkembanganBayi as $data) {
        $data->berat = $this->formatNumber($data->berat);
        $data->tinggi = $this->formatNumber($data->tinggi);
    }

    return view('admin.perkembanganbayi', compact('user', 'bayi', 'perkembanganBayi'));
}

// Tambahkan helper kecil di dalam controller
private function formatNumber($value)
{
    if ($value === null) return '-';
    $num = floatval($value);
    return fmod($num, 1) == 0 ? number_format($num, 0, '.', '') : number_format($num, 1, '.', '');
}

    /**
     * Simpan data bayi baru.
     */
   public function store(Request $request, User $user)
    {
        // Ambil semua input dulu
        $data = $request->all();

        // Normalisasi: ganti koma jadi titik untuk field numerik yang mungkin pakai koma
        foreach (['berat', 'tinggi', 'volume_asi', 'kalori_per_porsi', 'jumlah_porsi_per_hari'] as $f) {
            if (isset($data[$f]) && $data[$f] !== null && $data[$f] !== '') {
                $data[$f] = str_replace(',', '.', $data[$f]);
            } else {
                // pastikan key ada dan null bila kosong supaya validator bekerja konsisten
                $data[$f] = $data[$f] ?? null;
            }
        }

        // Validator: gunakan $validator variable yang nyata
        $validator = Validator::make($data, [
      'nama_bayi'      => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/',
        'tanggal_lahir'  => 'required|date|before_or_equal:today',
        'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
        'berat'          => 'nullable|numeric|min:2|max:15',
        'tinggi'         => 'nullable|numeric|min:40|max:100',
        'jenis_susu'     => 'nullable|in:ASI,Sufor,Mix',
        'volume_asi'     => 'nullable|numeric|min:200|max:1000',
        'kalori_per_porsi' => 'nullable|numeric|min:20|max:120',
        'jumlah_porsi_per_hari' => 'nullable|numeric|min:1|max:8',
    ], [
    'nama_bayi.required' => 'Nama bayi wajib diisi.',
    'nama_bayi.max' => 'Nama bayi tidak boleh lebih dari 100 karakter.',
    'nama_bayi.regex' => 'Nama hanya boleh mengandung huruf dan spasi.',
    'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
    'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini.',
    'jenis_kelamin.required' => 'Jenis kelamin bayi wajib dipilih.',
    'jenis_kelamin.in' => 'Jenis kelamin bayi harus Laki-laki atau Perempuan.',

    'berat.numeric' => 'Berat bayi harus berupa angka.',
    'berat.min' => 'Berat bayi tidak boleh kurang dari 2 kg.',
    'berat.max' => 'Berat bayi tidak boleh lebih dari 15 kg.',

    'tinggi.numeric' => 'Tinggi bayi harus berupa angka.',
    'tinggi.min' => 'Tinggi bayi tidak boleh kurang dari 40 cm.',
    'tinggi.max' => 'Tinggi bayi tidak boleh lebih dari 100 cm.',

    'volume_asi.numeric' => 'Volume ASI harus berupa angka.',
    'volume_asi.min' => 'Volume ASI minimal 100 ml per hari.',
    'volume_asi.max' => 'Volume ASI maksimal 1000 ml per hari.',

    'kalori_per_porsi.numeric' => 'Kalori per porsi harus berupa angka.',
    'kalori_per_porsi.min' => 'Kalori per porsi minimal 20 kkal.',
    'kalori_per_porsi.max' => 'Kalori per porsi maksimal 120 kkal.',

    'jumlah_porsi_per_hari.numeric' => 'Jumlah porsi per hari harus berupa angka.',
    'jumlah_porsi_per_hari.min' => 'Jumlah porsi minimal 1 kali per hari.',
    'jumlah_porsi_per_hari.max' => 'Jumlah porsi maksimal 10 kali per hari.',
    ]);

if ($validator->fails()) {
    return response()->json([
        'success' => false,
        'message' => $validator->errors()->first(), // ambil pesan pertama yang error
        'errors'  => $validator->errors()           // simpan semua kalau mau ditampilkan per field
    ], 422);
    }

        // Hitung umur bayi (bulan)
        $umur_bulan = Carbon::parse($data['tanggal_lahir'])->diffInMonths(now());


        // Validasi berdasarkan jenis_susu
        $jenis_susu = $data['jenis_susu'] ?? null;
        if ($jenis_susu === 'ASI' && empty($data['volume_asi'])) {
            return response()->json(['message' => 'Volume ASI harus diisi untuk bayi ASI.'], 422);
        }
        if ($jenis_susu === 'Sufor' && (empty($data['kalori_per_porsi']) || empty($data['jumlah_porsi_per_hari']))) {
            return response()->json(['message' => 'Kalori per porsi dan jumlah porsi per hari harus diisi untuk bayi Sufor.'], 422);
        }
        if ($jenis_susu === 'Mix' && empty($data['volume_asi']) && (empty($data['kalori_per_porsi']) || empty($data['jumlah_porsi_per_hari']))) {
            return response()->json(['message' => 'Minimal salah satu sumber nutrisi (ASI atau Sufor) harus diisi untuk bayi Mix.'], 422);
        }

        // Pastikan relasi user->bayis() ada di model User
        // Simpan data bayi
        $bayi = $user->bayis()->create($data);

        // Catat perkembangan (buat catatan awal)
        if (!empty($data['berat']) || !empty($data['tinggi'])) {
            PerkembanganBayi::create([
                'bayi_id' => $bayi->id,
                'tanggal_catat' => now(),
                'umur_bulan' => $umur_bulan,
                'berat' => $data['berat'] ?? null,
                'tinggi' => $data['tinggi'] ?? null,
            ]);
        }

        $bayis = $user->bayis()->latest()->get();

        return response()->json([
            'message' => 'Data bayi berhasil ditambahkan.',
            'bayis' => $bayis
        ]);
    }

    public function getData(User $user)
{
    $bayis = $user->bayis()->latest()->get();
    return response()->json($bayis);
}

/**
 * Update data bayi.
 */
public function update(Request $request, User $user, Bayi $bayi)
{
    $request->validate([
      'nama_bayi'      => 'required|string|max:100',
        'tanggal_lahir'  => 'required|date|before_or_equal:today',
        'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
        'berat'          => 'nullable|numeric|min:2|max:15',
        'tinggi'         => 'nullable|numeric|min:40|max:100',
        'jenis_susu'     => 'nullable|in:ASI,Sufor,Mix',
        'volume_asi'     => 'nullable|numeric|min:200|max:1000',
        'kalori_per_porsi' => 'nullable|numeric|min:20|max:120',
        'jumlah_porsi_per_hari' => 'nullable|numeric|min:1|max:8',
    ], [
    'nama_bayi.required' => 'Nama bayi wajib diisi.',
    'nama_bayi.max' => 'Nama bayi tidak boleh lebih dari 100 karakter.',
    'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
    'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini.',
    'jenis_kelamin.required' => 'Jenis kelamin bayi wajib dipilih.',
    'jenis_kelamin.in' => 'Jenis kelamin bayi harus Laki-laki atau Perempuan.',

    'berat.numeric' => 'Berat bayi harus berupa angka.',
    'berat.min' => 'Berat bayi tidak boleh kurang dari 2 kg.',
    'berat.max' => 'Berat bayi tidak boleh lebih dari 15 kg.',

    'tinggi.numeric' => 'Tinggi bayi harus berupa angka.',
    'tinggi.min' => 'Tinggi bayi tidak boleh kurang dari 40 cm.',
    'tinggi.max' => 'Tinggi bayi tidak boleh lebih dari 100 cm.',

    'volume_asi.numeric' => 'Volume ASI harus berupa angka.',
    'volume_asi.min' => 'Volume ASI minimal 200 ml per hari.',
    'volume_asi.max' => 'Volume ASI maksimal 1000 ml per hari.',

    'kalori_per_porsi.numeric' => 'Kalori per porsi harus berupa angka.',
    'kalori_per_porsi.min' => 'Kalori per porsi minimal 20 kkal.',
    'kalori_per_porsi.max' => 'Kalori per porsi maksimal 120 kkal.',

    'jumlah_porsi_per_hari.numeric' => 'Jumlah porsi per hari harus berupa angka.',
    'jumlah_porsi_per_hari.min' => 'Jumlah porsi minimal 1 kali per hari.',
    'jumlah_porsi_per_hari.max' => 'Jumlah porsi maksimal 10 kali per hari.',
    ]);

    try {
        // ðŸ”¹ Normalisasi angka koma (jika user input pakai koma)
        $berat = $request->berat ? str_replace(',', '.', $request->berat) : null;
        $tinggi = $request->tinggi ? str_replace(',', '.', $request->tinggi) : null;
        $volume_asi = $request->volume_asi ? str_replace(',', '.', $request->volume_asi) : null;
        $kalori_per_porsi = $request->kalori_per_porsi ? str_replace(',', '.', $request->kalori_per_porsi) : null;
        $jumlah_porsi_per_hari = $request->jumlah_porsi_per_hari ? str_replace(',', '.', $request->jumlah_porsi_per_hari) : null;

        // ðŸŽ¯ Hitung umur bayi
        $umur_bulan = Carbon::parse($request->tanggal_lahir)->diffInMonths(now());


        // ðŸ§ƒ Validasi tambahan berdasarkan jenis susu
        $jenis_susu = $request->jenis_susu;

        if ($jenis_susu === 'ASI') {
            if (empty($volume_asi)) {
                return response()->json([
                    'message' => 'Volume ASI harus diisi untuk bayi ASI.'
                ], 422);
            }
        }

        if ($jenis_susu === 'Sufor') {
            if (empty($kalori_per_porsi) || empty($jumlah_porsi_per_hari)) {
                return response()->json([
                    'message' => 'Kalori per porsi dan jumlah porsi per hari harus diisi untuk bayi Sufor.'
                ], 422);
            }
        }

        if ($jenis_susu === 'Mix') {
            if (empty($volume_asi) && (empty($kalori_per_porsi) || empty($jumlah_porsi_per_hari))) {
                return response()->json([
                    'message' => 'Minimal salah satu sumber nutrisi (ASI atau Sufor) harus diisi untuk bayi Mix.'
                ], 422);
            }
        }

        // ðŸ”¹ Update data bayi
        $bayi->update([
            'nama_bayi' => $request->nama_bayi,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'berat' => $berat,
            'tinggi' => $tinggi,
            'volume_asi' => $volume_asi,
            'jenis_susu' => $jenis_susu,
            'kalori_per_porsi' => $kalori_per_porsi,
            'jumlah_porsi_per_hari' => $jumlah_porsi_per_hari,
        ]);

        // ðŸ”¹ Ambil perkembangan hari ini & terakhir
        $perkembanganHariIni = $bayi->perkembanganBayi()
            ->whereDate('tanggal_catat', Carbon::today())
            ->first();

        $perkembanganTerakhir = $bayi->perkembanganBayi()
            ->latest('tanggal_catat')
            ->first();

        $beratBerubah = $perkembanganTerakhir ? $bayi->berat != $perkembanganTerakhir->berat : true;
        $tinggiBerubah = $perkembanganTerakhir ? $bayi->tinggi != $perkembanganTerakhir->tinggi : true;

        // ðŸ”¹ Buat atau update catatan perkembangan hari ini
        if ($perkembanganHariIni) {
            if ($beratBerubah || $tinggiBerubah) {
                $perkembanganHariIni->update([
                    'berat' => $bayi->berat,
                    'tinggi' => $bayi->tinggi,
                    'umur_bulan' => $umur_bulan,
                ]);
            }
        } else {
            if ($beratBerubah || $tinggiBerubah) {
                $bayi->perkembanganBayi()->create([
                    'tanggal_catat' => now(),
                    'umur_bulan' => $umur_bulan,
                    'berat' => $bayi->berat,
                    'tinggi' => $bayi->tinggi,
                ]);
            }
        }

        // ðŸ”¹ Ambil ulang data bayi terbaru
        $bayis = $user->bayis()->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Data bayi berhasil diperbarui.',
            'bayis' => $bayis,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
        ], 500);
    }
}

    /**
     * Hapus bayi.
     */
    public function destroy(User $user, Bayi $bayi)
    {
        $bayi->delete();
        
        // Ambil data terbaru setelah penghapusan
        $bayis = $user->bayis()->latest()->get();

        return response()->json([
            'message' => 'Data bayi berhasil dihapus.',
            'bayis' => $bayis
        ]);
    }





// Update or Create Bayi di modal bayi landingpage
public function updateOrCreate(Request $request)
{
    $request->validate([
        'nama_bayi'      => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/',
        'tanggal_lahir'  => 'required|date|before_or_equal:today',
        'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
        'berat'          => 'nullable|numeric|min:2|max:15',
        'tinggi'         => 'nullable|numeric|min:40|max:100',
        'jenis_susu'     => 'nullable|in:ASI,Sufor,Mix',
        'volume_asi'     => 'nullable|numeric|min:200|max:1000',
        'kalori_per_porsi' => 'nullable|numeric|min:20|max:120',
        'jumlah_porsi_per_hari' => 'nullable|numeric|min:1|max:8',
    ],[
        'nama_bayi.required' => 'Nama bayi harus diisi.',
        'nama_bayi.max' => 'Nama bayi tidak boleh lebih dari 100 karakter.',
        'nama_bayi.regex' => 'Nama hanya boleh mengandung huruf dan spasi.',
        'tanggal_lahir.required' => 'Tanggal lahir harus diisi.',
        'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini.',
        'jenis_kelamin.required' => 'Jenis kelamin harus diisi.',
        'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki atau Perempuan.',
        'berat.numeric' => 'Berat harus berupa angka.',
        'tinggi.numeric' => 'Tinggi harus berupa angka.',
        'volume_asi.numeric' => 'Volume ASI harus berupa angka.',
        'kalori_per_porsi.numeric' => 'Kalori per porsi harus berupa angka.',
        'jumlah_porsi_per_hari.numeric' => 'Jumlah porsi per hari harus berupa angka.',
        'jenis_susu.required' => 'Jenis susu harus diisi.',
        'volume_asi.min' => 'Volume ASI minimal 200 ml.',
        'volume_asi.max' => 'Volume ASI maksimal 1000 ml.',
        'kalori_per_porsi.min' => 'Kalori per porsi minimal 20 kkal.',
        'kalori_per_porsi.max' => 'Kalori per porsi maksimal 120 kkal.',
        'jumlah_porsi_per_hari.min' => 'Jumlah porsi per hari minimal 1 porsi.',
        'jumlah_porsi_per_hari.max' => 'Jumlah porsi per hari maksimal 8 porsi.',
    ]);

    try {
        $data = [
            'nama_bayi' => $request->nama_bayi,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'berat' => $request->berat ?: null,
            'tinggi' => $request->tinggi ?: null,
            'jenis_susu' => $request->jenis_susu,
            'volume_asi' => $request->volume_asi ?: null,
            'kalori_per_porsi' => $request->kalori_per_porsi ?: null,
            'jumlah_porsi_per_hari' => $request->jumlah_porsi_per_hari ?: null,
        ];

       // Logika susu
if ($request->jenis_susu === 'ASI') {

    $data['kalori_per_porsi'] = null;
    $data['jumlah_porsi_per_hari'] = null;

} elseif ($request->jenis_susu === 'Sufor') {

    $data['volume_asi'] = null;

} elseif ($request->jenis_susu === 'Mix') {

    // Kalau tidak diisi, anggap null (simpan biasa)
    $data['volume_asi'] = $request->volume_asi ?: null;
    $data['kalori_per_porsi'] = $request->kalori_per_porsi ?: null;
    $data['jumlah_porsi_per_hari'] = $request->jumlah_porsi_per_hari ?: null;
}

        $id = $request->input('id');
        $userId = auth()->id();

        $bayi = $id ? Bayi::where('user_id', $userId)->find($id) : null;

        if ($id && !$bayi) {
            return response()->json(['success' => false, 'message' => 'Data bayi tidak ditemukan.'], 404);
        }

        // Simpan data bayi
        $bayi
            ? $bayi->update($data)
            : $bayi = Bayi::create(array_merge($data, ['user_id' => $userId]));

        // --- CEGAH DUPLIKASI & UPDATE CATATAN HARI INI ---
        $perkembanganHariIni = $bayi->perkembanganBayi()
            ->whereDate('tanggal_catat', Carbon::today())
            ->first();

        $perkembanganTerakhir = $bayi->perkembanganBayi()
            ->latest('tanggal_catat')
            ->first();

        $beratBerubah = $perkembanganTerakhir ? $bayi->berat != $perkembanganTerakhir->berat : true;
        $tinggiBerubah = $perkembanganTerakhir ? $bayi->tinggi != $perkembanganTerakhir->tinggi : true;

        // Tambah atau update perkembangan hari ini
        if ($bayi->wasRecentlyCreated) {
            // Bayi baru dibuat â†’ buat catatan awal
            $bayi->perkembanganBayi()->create([
                'berat' => $bayi->berat,
                'tinggi' => $bayi->tinggi,
                'umur_bulan' => Carbon::parse($bayi->tanggal_lahir)->diffInMonths(Carbon::now()),
                'tanggal_catat' => Carbon::now(),
            ]);
        } else {
            if ($perkembanganHariIni) {
                // Sudah ada catatan hari ini â†’ update kalau ada perubahan
                if ($beratBerubah || $tinggiBerubah) {
                    $perkembanganHariIni->update([
                        'berat' => $bayi->berat,
                        'tinggi' => $bayi->tinggi,
                        'umur_bulan' => Carbon::parse($bayi->tanggal_lahir)->diffInMonths(Carbon::now()),
                    ]);
                }
            } else {
                // Belum ada catatan hari ini â†’ buat baru kalau ada perubahan
                if ($beratBerubah || $tinggiBerubah) {
                    $bayi->perkembanganBayi()->create([
                        'berat' => $bayi->berat,
                        'tinggi' => $bayi->tinggi,
                        'umur_bulan' => Carbon::parse($bayi->tanggal_lahir)->diffInMonths(Carbon::now()),
                        'tanggal_catat' => Carbon::now(),
                    ]);
                }
            }
        }

        // Muat ulang relasi
        $bayi->load(['user', 'perkembanganBayi']);

        $isComplete = $bayi->berat && $bayi->tinggi && (
            $bayi->volume_asi || 
            ($bayi->kalori_per_porsi && $bayi->jumlah_porsi_per_hari)
        );

        return response()->json([
            'success'      => true,
            'message'      => 'Data bayi berhasil disimpan.',
            'bayis'        => Bayi::where('user_id', $userId)->get(),
            'selectedBayi' => $bayi,
            'redirect'     => $isComplete ? route('rekomendasi', ['bayi_id' => $bayi->id]) : null,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
        ], 500);
    }
}



// Update data bayi dari halaman hasil rekomendasi (ANTI DUPLIKAT)
public function updateFromRekomendasi(Request $request, $id)
{
    $request->validate([
        'nama_bayi'      => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/',
        'tanggal_lahir'  => 'required|date|before_or_equal:today',
        'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
        'berat'          => 'nullable|numeric|min:2|max:15',
        'tinggi'         => 'nullable|numeric|min:40|max:100',
        'jenis_susu'     => 'required|in:ASI,Sufor,Mix',
        'volume_asi'     => 'nullable|numeric|min:200|max:1000',
        'kalori_per_porsi' => 'nullable|numeric|min:20|max:120',
        'jumlah_porsi_per_hari' => 'nullable|numeric|min:1|max:8',
    ], [
        'nama_bayi.required' => 'Nama bayi harus diisi.',
        'nama_bayi.regex' => 'Nama hanya boleh mengandung huruf dan spasi.',
        'nama_bayi.max' => 'Nama bayi tidak boleh lebih dari 100 karakter.',
        'tanggal_lahir.required' => 'Tanggal lahir harus diisi.',
        'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini.',
        'jenis_kelamin.required' => 'Jenis kelamin harus diisi.',
        'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki atau Perempuan.',
        'berat.numeric' => 'Berat harus berupa angka.',
        'tinggi.numeric' => 'Tinggi harus berupa angka.',
        'volume_asi.numeric' => 'Volume ASI harus berupa angka.',
        'kalori_per_porsi.numeric' => 'Kalori per porsi harus berupa angka.',
        'jumlah_porsi_per_hari.numeric' => 'Jumlah porsi per hari harus berupa angka.',
        'jenis_susu.required' => 'Jenis susu harus diisi.',
        'volume_asi.min' => 'Volume ASI minimal 200 ml.',
        'volume_asi.max' => 'Volume ASI maksimal 1000 ml.',
        'kalori_per_porsi.min' => 'Kalori per porsi minimal 20 kkal.',
        'kalori_per_porsi.max' => 'Kalori per porsi maksimal 120 kkal.',
        'jumlah_porsi_per_hari.min' => 'Jumlah porsi per hari minimal 1 porsi.',
        'jumlah_porsi_per_hari.max' => 'Jumlah porsi per hari maksimal 8 porsi.',
    ]);

    $bayi = Bayi::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    // =========================
    // Update data bayi
    // =========================
    $data = [
        'nama_bayi'     => $request->nama_bayi,
        'tanggal_lahir' => $request->tanggal_lahir,
        'jenis_kelamin' => $request->jenis_kelamin,
        'berat'         => $request->berat ?: null,
        'tinggi'        => $request->tinggi ?: null,
        'jenis_susu'    => $request->jenis_susu,
    ];

    // Logika susu
    if ($request->jenis_susu === 'ASI') {
        $data['volume_asi'] = $request->volume_asi ?: null;
        $data['kalori_per_porsi'] = null;
        $data['jumlah_porsi_per_hari'] = null;
    } elseif ($request->jenis_susu === 'Sufor') {
        $data['volume_asi'] = null;
        $data['kalori_per_porsi'] = $request->kalori_per_porsi ?: null;
        $data['jumlah_porsi_per_hari'] = $request->jumlah_porsi_per_hari ?: null;
    } else { // Mix
        $data['volume_asi'] = $request->volume_asi ?: null;
        $data['kalori_per_porsi'] = $request->kalori_per_porsi ?: null;
        $data['jumlah_porsi_per_hari'] = $request->jumlah_porsi_per_hari ?: null;
    }

    $bayi->update($data);

    // =========================
    // SIMPAN PERKEMBANGAN (ANTI DUPLIKAT)
    // =========================
    $umurBulan = Carbon::parse($bayi->tanggal_lahir)
        ->diffInMonths(Carbon::now());

    $perkembanganHariIni = $bayi->perkembanganBayi()
        ->whereDate('tanggal_catat', Carbon::today())
        ->first();

    $perkembanganTerakhir = $bayi->perkembanganBayi()
        ->latest('tanggal_catat')
        ->first();

    $beratBerubah = $perkembanganTerakhir
        ? $bayi->berat != $perkembanganTerakhir->berat
        : true;

    $tinggiBerubah = $perkembanganTerakhir
        ? $bayi->tinggi != $perkembanganTerakhir->tinggi
        : true;

    if ($perkembanganHariIni) {
        // Hari sama → update jika berubah
        if ($beratBerubah || $tinggiBerubah) {
            $perkembanganHariIni->update([
                'berat' => $bayi->berat,
                'tinggi' => $bayi->tinggi,
                'umur_bulan' => $umurBulan,
            ]);
        }
    } else {
        // Hari beda → buat baru HANYA jika berubah
        if ($beratBerubah || $tinggiBerubah) {
            $bayi->perkembanganBayi()->create([
                'berat' => $bayi->berat,
                'tinggi' => $bayi->tinggi,
                'umur_bulan' => $umurBulan,
                'tanggal_catat' => Carbon::now(),
            ]);
        }
    }

    $bayi->load(['user', 'perkembanganBayi']);

    return response()->json([
        'success'  => true,
        'message'  => 'Data bayi berhasil diperbarui.',
        'redirect' => route('rekomendasi', ['bayi_id' => $bayi->id]),
    ]);
}

}