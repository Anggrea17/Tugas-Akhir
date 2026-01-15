<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">
    <meta charset="UTF-8">
    <title>Kelola Bayi - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

</head>

<body class="bg-gray-100 min-h-screen" x-cloak x-data="bayiHandler()" x-init="init()">

    <!-- Sidebar -->
    <aside
        class="fixed z-30 inset-y-0 left-0 w-64 bg-white shadow-md h-screen transform md:translate-x-0 transition-transform duration-200 ease-in-out"
        :class="{ '-translate-x-full': !sidebarOpen }">
        <div class="p-6">
            <img src="{{ asset('bahan/lognav.png') }}" alt="Logo" />
            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-yellow-100 rounded">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3"></path>
                    </svg>
                    Dashboard
                </a>
     <a href="{{ route('admin.users') }}"
                    class="flex items-center px-4 py-2 text-gray-700 bg-yellow-100 rounded">
                    <svg class="w-5 h-5 mr-3 text-yellow-700"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path
                            d="M5.121 17.804A4 4 0 017.757 16h8.486a4 4 0 012.636 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z 125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z">
                        </path>
                    </svg>
                    Kelola User
                </a>
                <a href="{{ route('admin.mpasi') }}"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-yellow-100 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 mr-3 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    Kelola Resep MPASI
                </a>
                <a href="{{ route('admin.kelolaartikel') }}"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-yellow-100 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 mr-3 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                    Kelola Artikel
                </a>
                <a href="{{ route('admin.kategoriartikel') }}"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-yellow-100 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.53 16.122a3 3 0 0 0-5.716 0A9.754 9.754 0 0 0 3 18.375v2.25H21v-2.25a9.754 9.754 0 0 0-1.584-2.253m-11.397-3.263l2.559 2.559c.351.351.86.643 1.41.874m-2.288-3.328c.847.476 1.765.873 2.73 1.148M3 14.166V5.25a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 5.25v8.916m-16.142 6.642v.002v.002a.75.75 0 0 0-.75.75v.002a.75.75 0 0 0 .75.75H21v-.002a.75.75 0 0 0-.75-.75h-15.358Z" />
                    </svg>
                    Kelola Kategori Artikel
                </a>
                <a href="{{ route('admin.kategorimpasi') }}"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-yellow-100 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.015 3.055a1 1 0 0 1 1.26 1.548l-.098.079l-2.101 1.501a1.96 1.96 0 0 0-.794 1.937l.032.152l3.343-3.343a1 1 0 0 1 1.497 1.32l-.083.094l-3.343 3.343c.705.18 1.485-.04 1.986-.63l.103-.132l1.501-2.101a1 1 0 0 1 1.694 1.055l-.067.107l-1.5 2.102a3.97 3.97 0 0 1-5.054 1.216l-.18-.1l-2.297 2.296l4.157 4.158a1 1 0 0 1 .083 1.32l-.083.094a1 1 0 0 1-1.32.083l-.094-.083l-4.157-4.158l-4.157 4.158a1 1 0 0 1-1.32.083l-.094-.083a1 1 0 0 1-.083-1.32l.083-.094l4.157-4.158l-1.61-1.61a4.5 4.5 0 0 1-1.355.473l-.25.037a3.89 3.89 0 0 1-3.279-1.15C2.663 10.319 2.132 9.15 2 8.027c-.13-1.105.12-2.289.93-3.098c.809-.81 1.992-1.06 3.097-.93c1.123.133 2.293.664 3.222 1.593a3.89 3.89 0 0 1 1.15 3.278c-.06.505-.207.984-.406 1.401l-.104.204l1.61 1.61l2.298-2.296a3.97 3.97 0 0 1 .944-5.103l.172-.13z" />
                    </svg>
                    Kelola Kategori Mpasi
                </a>
            </nav>
        </div>
    </aside>

    <!-- Overlay Mobile -->
    <div class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden" x-show="sidebarOpen" @click="sidebarOpen = false"
        x-transition.opacity></div>

    <!-- Main Content Layout -->
    <div class="flex-1 md:ml-64">

        <!-- Navbar -->
        <nav class="bg-yellow-400 p-4 shadow-md flex justify-between items-center">
            <!-- Hamburger (mobile only) -->
            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-700 focus:outline-none">
                <template x-if="!sidebarOpen">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </template>
                <template x-if="sidebarOpen">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </template>
            </button>

            <!-- Profil Admin Dropdown -->
            <div class="relative ml-auto" x-cloak x-data="{ open: false }">
                <button @click="open = !open" class="focus:outline-none">
                    <img src="{{ asset('bahan/admin-icon.png') }}" alt="Admin"
                        class="w-10 h-10 rounded-full border-2 border-white shadow">
                </button>

                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-xl text-gray-800 p-4 z-50">
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('bahan/admin-icon.png') }}" alt="Admin" class="w-14 h-14 rounded-full">
                        <div>
                            <p class="font-semibold text-lg">{{ auth()->user()->nama }}</p>
                            <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Konten Utama -->
        <div class="max-w-6xl mx-auto mt-8 px-4">
            <!-- Header dengan tombol kembali -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.users') }}" class="text-gray-600 hover:text-gray-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-700">Kelola Bayi</h2>
                        <p class="text-gray-600">User: <span class="font-medium">{{ $user->nama }}</span></p>
                    </div>
                </div>

                <!-- Tombol Tambah Bayi -->
                <button @click="openTambahModal()" class="focus:outline-none" title="Tambah User">
                    <img src="{{ asset('bahan/plusicon.png') }}" alt="Tambah User" class="w-8 h-8">
                </button>
            </div>

            <!-- Alert Messages -->
            <div x-show="alert.show" x-transition class="p-4 rounded mb-4"
                :class="alert.type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                <p x-text="alert.message"></p>
            </div>

            <!-- Table Bayi -->
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full">
                    <thead class="bg-yellow-400 text-white">
                        <tr>
                            <th class="text-left py-3 px-4">No</th>
                            <th class="text-left py-3 px-4">Nama Bayi</th>
                            <th class="text-left py-3 px-4">Jenis Kelamin</th>
                            <th class="text-left py-3 px-4">Tanggal Lahir</th>
                            <th class="text-left py-3 px-4">Umur</th>
                            <th class="text-left py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-if="bayis.length === 0">
                            <tr>
                                <td colspan="8" class="text-center py-8 text-gray-500">
                                    Belum ada data bayi untuk user ini.
                                </td>
                            </tr>
                        </template>
                        <template x-for="(bayi, index) in bayis" :key="bayi.id">
                            <tr class="hover:bg-yellow-50 border-b">
                                <td class="py-3 px-4" x-text="index + 1"></td>
                                <td class="py-3 px-4" x-text="bayi.nama_bayi"></td>
                                <td class="py-3 px-4" x-text="bayi.jenis_kelamin"></td>
                                <td class="py-3 px-4" x-text="formatDate(bayi.tanggal_lahir)"></td>
                                <td class="py-3 px-4" x-text="calculateAge(bayi.tanggal_lahir)"></td>
                                <td class="py-3 px-4">
                                    <div class="flex flex-wrap gap-1">
                                        <button @click="openLihatModal(bayi)"
                                            class="bg-lime-500 text-white px-3 py-1 rounded text-sm hover:bg-lime-600">
                                            Lihat
                                        </button>
                                        <button @click="openEditModal(bayi)"
                                            class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                            Edit
                                        </button>
                                        <button @click="openHapusModal(bayi)"
                                            class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                            Hapus
                                        </button>
                                        <!-- button ke halaman perkembangan bayi -->
                                        <a :href="`/kelola-bayi/{{ $user->id }}/perkembangan/${bayi.id}`"
                                            class="bg-purple-500 text-white px-3 py-1 rounded text-sm hover:bg-purple-600">
                                            Perkembangan
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Modal Tambah Bayi -->
            <div x-show="modalTambah" x-cloak x-transition
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg w-full max-w-md max-h-[90vh] overflow-y-auto relative">
                
                 <button @click="closeAllModals()" class="absolute top-4 right-4 text-gray-500 hover:text-red-600 text-2xl font-bold">&times;</button>
                    <h3 class="text-lg font-semibold mb-4 text-yellow-600">Tambah Data Bayi</h3>
                    <form @submit.prevent="submitTambah()">
                        <div class="space-y-3">
                            <input type="text" x-model="formTambah.nama_bayi" placeholder="Nama Bayi" required
                                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">

                            <div class="relative">
                                <input type="date" id="tanggal_lahir" x-model="formTambah.tanggal_lahir" required
                                    class="peer block w-full rounded-lg border border-gray-300 bg-white px-3 pt-5 pb-2 text-sm text-gray-900 placeholder-transparent focus:border-yellow-500 focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                                    placeholder="Tanggal Lahir" />

                                <label for="tanggal_lahir"
                                    class="absolute left-3 top-2 text-sm text-gray-500 transition-all 
      peer-placeholder-shown:top-5 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base
      peer-focus:top-2 peer-focus:text-sm peer-focus:text-yellow-600 bg-white px-1">
                                    Tanggal Lahir
                                </label>
                            </div>

                            <select x-model="formTambah.jenis_kelamin" required
                                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-Laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>

                            <input type="number" step="0.1" min="2" max="15" x-model="formTambah.berat" placeholder="Berat (kg)"
                                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">

                            <input type="number" step="0.1" min="40" max="100" x-model="formTambah.tinggi"
                                placeholder="Tinggi (cm)"
                                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                            <!-- pilih jenis susu -->
                            <select x-model="formTambah.jenis_susu"
                                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                <option value="">Pilih Jenis Susu</option>
                                <option value="ASI">ASI</option>
                                <option value="Sufor">Susu formula</option>
                                <option value="Mix">Campuran (ASI dan Susu formula)</option>
                            </select>
                        </div>
                        <!-- memunculkan input ASI saat user memilih jenis susu ASI dan mix -->
                        <div x-show="formTambah.jenis_susu === 'ASI' || formTambah.jenis_susu === 'Mix'" x-transition>
                            <input type="number" step="0.1" min="200" max="1000" x-model="formTambah.volume_asi"
                                placeholder="Volume ASI (ml, opsional)"
                                class="w-full border rounded px-3 py-2 mt-3 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        </div>
                        <!-- memunculkan input kalori dan porsi saat user memilih jenis susu sufor dan mix -->
                        <div x-show="formTambah.jenis_susu === 'Sufor' || formTambah.jenis_susu === 'Mix'"
                            x-transition>
                            <input type="number" step="0.1" min="20" max="120" x-model="formTambah.kalori" placeholder="Kalori per Porsi (kcal)"
                                class="w-full border rounded px-3 py-2 mt-3 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                            <input type="number" step="1" min="1" max="8" x-model="formTambah.porsi" placeholder="Jumlah Porsi per Hari"
                                class="w-full border rounded px-3 py-2 mt-3 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        </div>

                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" @click="closeAllModals()"
                                class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                                Batal
                            </button>
                            <button type="submit" :disabled="loading"
                                class="bg-yellow-400 text-white px-4 py-2 rounded hover:bg-yellow-500 disabled:opacity-50">
                                <span x-show="!loading">Simpan</span>
                                <span x-show="loading">Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Edit Bayi -->
            <div x-show="modalEdit" x-cloak x-transition
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg w-full max-w-md
                max-h-[90vh] overflow-y-auto relative">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-yellow-600">Edit Data Bayi</h3>
    
                
                 <button @click="closeAllModals()" class="absolute top-4 right-4 text-gray-500 hover:text-red-600 text-2xl font-bold">&times;</button>
                    </div>
                    <form @submit.prevent="submitEdit()">
                        <div class="space-y-3">
                            <input type="text" x-model="formEdit.nama_bayi" placeholder="Nama Bayi" required
                                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">

                            <div class="relative">
                                <input type="date" id="tanggal_lahir_edit" x-model="formEdit.tanggal_lahir"
                                    required
                                    class="peer block w-full rounded-lg border border-gray-300 bg-white px-3 pt-5 pb-2 text-sm text-gray-900 placeholder-transparent focus:border-yellow-500 focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                                    placeholder="Tanggal Lahir" />

                                <label for="tanggal_lahir_edit"
                                    class="absolute left-3 top-2 text-sm text-gray-500 transition-all 
      peer-placeholder-shown:top-5 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base
      peer-focus:top-2 peer-focus:text-sm peer-focus:text-yellow-600 bg-white px-1">
                                    Tanggal Lahir
                                </label>
                            </div>

                            <!-- Edit jenis kelamin data bayi -->
                            <select x-model="formEdit.jenis_kelamin" required
                                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-Laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>

                            <input type="number" step="0.1" min="2" max="15" x-model="formEdit.berat" placeholder="Berat (kg)"
                                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">

                            <input type="number" step="0.1" min="40" max="100" x-model="formEdit.tinggi" placeholder="Tinggi (cm)"
                                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                            <!-- pilih jenis susu -->
                            <select x-model="formEdit.jenis_susu"
                                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                <option value="">Pilih Jenis Susu</option>
                                <option value="ASI">ASI</option>
                                <option value="Sufor">Susu formula</option>
                                <option value="Mix">Campuran (ASI dan Susu formula)</option>
                            </select>
                            <!-- memunculkan input ASI saat user memilih jenis susu ASI dan mix -->
                            <input type="number" step="0.1" min="200" max="1000" x-model="formEdit.volume_asi"
                                placeholder="Jumlah ASI (ml)"
                                x-show="formEdit.jenis_susu === 'ASI' || formEdit.jenis_susu === 'Mix'" x-transition
                                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">

                            <!-- memunculkan input kalori dan porsi saat user memilih jenis susu sufor dan mix -->
                            <div x-show="formEdit.jenis_susu === 'Sufor' || formEdit.jenis_susu === 'Mix'"
                                x-transition>
                                <input type="number" step="0.1" x-model="formEdit.kalori_per_porsi"
                                    placeholder="Kalori per Porsi (kcal)" min="20" max="120"
                                    class="w-full border rounded px-3 py-2 mt-3 focus:outline-none focus:ring-2 focus:ring-yellow-400">

                                <input type="number" step="1" min="1" max="8" x-model="formEdit.jumlah_porsi_per_hari"
                                    placeholder="Jumlah Porsi"
                                    class="w-full border rounded px-3 py-2 mt-3 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" @click="closeAllModals()"
                                class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                                Batal
                            </button>
                            <button type="submit" :disabled="loading"
                                class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 disabled:opacity-50">
                                <span x-show="!loading">Simpan</span>
                                <span x-show="loading">Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

<!-- Modal Lihat Detail Bayi -->
<div x-show="modalLihat" x-cloak
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    @click.self="closeAllModals()"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90">

    <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden relative shadow-2xl">
        <!-- Tombol Close -->
        <button @click="closeAllModals()"
            class="absolute top-4 right-4 z-10 text-gray-500 hover:text-red-600 text-2xl font-bold">
            &times;
        </button>

        <div class="overflow-y-auto max-h-[90vh]">
            <!-- Header -->
            <div class="relative p-8 border-b">
                <h2 class="text-3xl font-bold text-gray-800 text-center" x-text="detailData.nama_bayi"></h2>
                <p class="text-center text-gray-500 mt-1">Detail Informasi Bayi</p>
            </div>

            <!-- Content -->
            <div class="p-8 space-y-6">
                <!-- Info Cards Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 p-4 rounded-xl text-center border border-blue-200">
                        <p class="text-xs text-blue-600 font-semibold mb-1">Jenis Kelamin</p>
                        <p class="text-lg font-bold text-blue-900" x-text="detailData.jenis_kelamin"></p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-xl text-center border border-purple-200">
                        <p class="text-xs text-purple-600 font-semibold mb-1">Umur</p>
                        <p class="text-lg font-bold text-purple-900" x-text="calculateAge(detailData.tanggal_lahir)"></p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-xl text-center border border-green-200">
                        <p class="text-xs text-green-600 font-semibold mb-1">Berat</p>
                        <p class="text-lg font-bold text-green-900"><span x-text="detailData.berat"></span> kg</p>
                    </div>
                    <div class="bg-pink-50 p-4 rounded-xl text-center border border-pink-200">
                        <p class="text-xs text-pink-600 font-semibold mb-1">Tinggi</p>
                        <p class="text-lg font-bold text-pink-900"><span x-text="detailData.tinggi"></span> cm</p>
                    </div>
                </div>

                <!-- Informasi Umum -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-200">
                    <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                        </svg>
                        Informasi Umum
                    </h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal Lahir:</span>
                            <span class="font-semibold text-gray-800" x-text="formatDate(detailData.tanggal_lahir)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jenis Susu:</span>
                            <span class="font-semibold text-gray-800" x-text="detailData.jenis_susu"></span>
                        </div>
                    </div>
                </div>

                <!-- Informasi Nutrisi (hanya muncul jika Sufor atau Mix) -->
                <div x-show="detailData.jenis_susu === 'Sufor' || detailData.jenis_susu === 'Mix'"
                    class="bg-gradient-to-r from-orange-50 to-yellow-50 p-6 rounded-xl border border-orange-200">
                    <h3 class="text-lg font-bold text-orange-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                        </svg>
                        Informasi Susu Formula
                    </h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-orange-600" x-text="detailData.kalori_per_porsi > 0 ? detailData.kalori_per_porsi : '-'"></p>
                            <p class="text-xs text-gray-600">Kalori per Porsi (kcal)</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-orange-600" x-text="detailData.jumlah_porsi_per_hari > 0 ? detailData.jumlah_porsi_per_hari : '-'"></p>
                            <p class="text-xs text-gray-600">Porsi per Hari</p>
                        </div>
                    </div>
                </div>

                <!-- Informasi ASI (hanya muncul jika ASI atau Mix) -->
                <div x-show="detailData.jenis_susu === 'ASI' || detailData.jenis_susu === 'Mix'"
                    class="bg-gradient-to-r from-green-50 to-teal-50 p-6 rounded-xl border border-green-200">
                    <h3 class="text-lg font-bold text-green-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                        </svg>
                        Informasi ASI
                    </h3>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-600" x-text="detailData.volume_asi > 0 ? detailData.volume_asi : '-'"></p>
                        <p class="text-xs text-gray-600">Volume ASI (ml)</p>
                    </div>
                </div>

                <!-- Tombol Tutup -->
                <div class="flex justify-end pt-4">
                    <button @click="closeAllModals()"
                        class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Hapus -->
        <div x-show="modalHapus" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto">

            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

            <!-- Modal container -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="modalHapus" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                    <!-- Modal content -->
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <!-- Warning icon -->
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>

                            <!-- Modal text -->
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">
                                    Hapus Data Bayi
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Apakah Anda yakin ingin menghapus bayi 
                                        <span class="font-medium text-gray-700" x-text="bayiToDelete?.nama_bayi"></span>? 
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal buttons -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <form id="hapusForm" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" @click="confirmHapus()"
                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                                Hapus
                            </button>
                        </form>
                        <button type="button" @click="closeAllModals()"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- JavaScript -->
        <script>
            function bayiHandler() {
                return {
                    sidebarOpen: false,
                    loading: false,

                    // Modal states
                    modalTambah: false,
                    modalEdit: false,
                    modalLihat: false,
                    modalHapus: false,

                    // Data
                    bayis: [],
                    userId: {{ $user->id ?? 0 }},

                    // Alert system
                    alert: {
                        show: false,
                        type: 'success', // success or error
                        message: ''
                    },

                    // Form data
                    formTambah: {
                        nama_bayi: '',
                        tanggal_lahir: '',
                        jenis_kelamin: '',
                        berat: '',
                        tinggi: '',
                        jenis_susu: '',
                        volume_asi: '',
                        kalori_per_porsi: '',
                        jumlah_porsi_per_hari: ''
                    },

                    formEdit: {
                        id: null,
                        nama_bayi: '',
                        tanggal_lahir: '',
                        jenis_kelamin: '',
                        berat: '',
                        tinggi: '',
                        jenis_susu: '',
                        volume_asi: '',
                        kalori_per_porsi: '',
                        jumlah_porsi_per_hari: ''
                    },

                    detailData: {},
                    bayiToDelete: null,

                    // Initialize
                    init() {
                        const token = document.querySelector('meta[name="csrf-token"]');
                        if (token) window.csrfToken = token.getAttribute('content');

                        this.loadBayiData();

                        // Tutup modal otomatis kalau ada error validasi
                        if (window.hasLaravelError) {
                            this.closeAllModals();
                        }
                    },



                    // Load bayi data
                    async loadBayiData() {
                        try {
                            const response = await fetch(`/kelola-bayi/${this.userId}/data`, {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': window.csrfToken
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                this.bayis = data;
                            } else {
                                this.showAlert('error', 'Gagal memuat data bayi');
                            }
                        } catch (error) {
                            console.error('Error loading bayi data:', error);
                            this.showAlert('error', 'Gagal memuat data bayi');
                        }
                    },

                    // Utility functions
                    formatDate(dateString) {
                        if (!dateString) return '-';
                        const date = new Date(dateString);
                        return date.toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'long', // ubah ke 'long' biar keluar nama bulan lengkap
                            year: 'numeric'
                        });
                    },

                    calculateAge(birthDate) {
                        if (!birthDate) return '-';
                        const today = new Date();
                        const birth = new Date(birthDate);

                        let months = today.getMonth() - birth.getMonth();
                        let years = today.getFullYear() - birth.getFullYear();

                        if (today.getDate() < birth.getDate()) {
                            months--;
                        }

                        if (months < 0) {
                            years--;
                            months += 12;
                        }

                        if (years > 0) {
                            return `${years} tahun ${months} bulan`;
                        } else {
                            return `${months} bulan`;
                        }
                    },

                    showAlert(type, message) {
                        this.alert = {
                            show: true,
                            type,
                            message
                        };
                        setTimeout(() => {
                            this.alert.show = false;
                        }, 5000);
                    },

                    // Modal functions
                    openTambahModal() {
                        this.resetFormTambah();
                        this.modalTambah = true;
                    },

                    openEditModal(bayi) {
                        this.formEdit = {
                            id: bayi.id,
                            nama_bayi: bayi.nama_bayi,
                            tanggal_lahir: bayi.tanggal_lahir,
                            jenis_kelamin: bayi.jenis_kelamin,
                            berat: bayi.berat,
                            tinggi: bayi.tinggi,
                            jenis_susu: bayi.jenis_susu,
                            kalori_per_porsi: bayi.kalori_per_porsi,
                            jumlah_porsi_per_hari: bayi.jumlah_porsi_per_hari,
                            volume_asi: bayi.volume_asi || ''
                        };
                        this.modalEdit = true;
                    },

                    openLihatModal(bayi) {
                        this.detailData = bayi;
                        this.modalLihat = true;
                    },

                    openHapusModal(bayi) {
                        this.bayiToDelete = bayi;
                        this.modalHapus = true;
                    },

                    closeAllModals() {
                        this.modalTambah = false;
                        this.modalEdit = false;
                        this.modalLihat = false;
                        this.modalHapus = false;
                        this.resetFormTambah();
                        this.detailData = {};
                        this.bayiToDelete = null;
                    },

                    resetFormTambah() {
                        this.formTambah = {
                            nama_bayi: '',
                            tanggal_lahir: '',
                            jenis_kelamin: '',
                            berat: '',
                            tinggi: '',
                            jenis_susu: '',
                            kalori_per_porsi: '',
                            jumlah_porsi_per_hari: '',
                            volume_asi: ''
                        };
                    },

                    // AJAX functions
// Update fungsi submitTambah()
async submitTambah() {
    this.loading = true;

    try {
        const response = await fetch(`/kelola-bayi/${this.userId}/store`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(this.formTambah)
        });

        const data = await response.json();

        if (response.ok) {
            this.bayis = data.bayis;
            this.showAlert('success', data.message);
            this.closeAllModals(); // ✅ Reset di sini karena berhasil
        } else {
            // ❌ JANGAN reset form saat error
            let errorMessage = data.message || 'Terjadi kesalahan';
            
            if (data.errors) {
                errorMessage = Object.values(data.errors).flat().join(', ');
            }
            
            // ✅ Tutup modal TANPA reset
            this.modalTambah = false;
            
            // Tampilkan error
            this.showAlert('error', errorMessage);
            
            // ✅ Buka kembali modal (data masih ada)
            setTimeout(() => {
                this.modalTambah = true;
            }, 5300);
        }
    } catch (error) {
        console.error('Error:', error);
        
        // ✅ Tutup modal TANPA reset
        this.modalTambah = false;
        this.showAlert('error', 'Terjadi kesalahan saat menambah data');
        
        setTimeout(() => {
            this.modalTambah = true;
        }, 5300);
    }
    
    this.loading = false;
},

// Update fungsi submitEdit()
async submitEdit() {
    this.loading = true;

    try {
        const response = await fetch(`/kelola-bayi/${this.userId}/update/${this.formEdit.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(this.formEdit)
        });

        const data = await response.json();

        if (response.ok) {
            this.bayis = data.bayis;
            this.showAlert('success', data.message);
            this.closeAllModals();
        } else {
            // Tampilkan error DAN tutup modal
            let errorMessage = data.message || 'Terjadi kesalahan';
            
            if (data.errors) {
                errorMessage = Object.values(data.errors).flat().join(', ');
            }
            
            // Tutup modal dulu
            this.closeAllModals();
            
            // Tampilkan error
            this.showAlert('error', errorMessage);
            
            // ✅ Buka kembali modal setelah 5 detik (saat error hilang)
            setTimeout(() => {
                this.modalEdit = true;
            }, 5300); // 5000ms alert + 300ms delay
        }
    } catch (error) {
        console.error('Error:', error);
        
        this.closeAllModals();
        this.showAlert('error', 'Terjadi kesalahan saat mengupdate data');
        
        // ✅ Buka kembali modal setelah error hilang
        setTimeout(() => {
            this.modalEdit = true;
        }, 5300);
    }

    this.loading = false;
},
                    async confirmHapus() {
                        this.loading = true;

                        try {
                            const response = await fetch(`/kelola-bayi/${this.userId}/delete/${this.bayiToDelete.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': window.csrfToken,
                                    'Accept': 'application/json'
                                }
                            });

                            const data = await response.json();

                            if (response.ok) {
                                this.bayis = data.bayis;
                                this.showAlert('success', data.message);
                                this.closeAllModals();
                            } else {
                                this.showAlert('error', data.message || 'Terjadi kesalahan');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            this.showAlert('error', 'Terjadi kesalahan saat menghapus data');
                        }

                        this.loading = false;
                    }
                }
            }
        </script>
        <script>
            document.addEventListener("alpine:init", () => {
                Alpine.store("errors", {
                    hasError: @json($errors->any())
                });
            });
        </script>

</body>

</html>
