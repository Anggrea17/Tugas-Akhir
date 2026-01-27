<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">
    <meta charset="UTF-8">
    <title>Perkembangan Bayi - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        .chart-container {
            position: relative;
            transition: transform 0.3s ease;
        }

        .chart-container:hover {
            transform: translateY(-5px);
        }
    </style>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen" x-data="perkembanganHandler()" x-init="init()">

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
                    <svg class="w-5 h-5 mr-3 text-yellow-700" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
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
                    Kelola Kategori MPASI
                </a>
            </nav>
        </div>
    </aside>

    <!-- Overlay Mobile -->
    <div class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden" x-show="sidebarOpen" @click="sidebarOpen = false"
        x-transition.opacity></div>

    <!-- Main Content -->
    <div class="flex-1 md:ml-64">

        <!-- Navbar -->
        <nav class="bg-yellow-400 p-4 shadow-md flex justify-between items-center">
            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-700 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="relative ml-auto" x-data="{ open: false }">
                <button @click="open = !open" class="focus:outline-none">
                    <img src="{{ asset('bahan/admin-icon.png') }}" alt="Admin"
                        class="w-10 h-10 rounded-full border-2 border-white shadow">
                </button>

                <div x-cloak x-show="open" @click.away="open = false"
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

        <!-- Content -->
        <div class="max-w-7xl mx-auto mt-8 px-4 pb-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.kelolabayi', $bayi->user_id) }}"
                        class="text-gray-600 hover:text-gray-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-700">Grafik Perkembangan Bayi</h2>
                        <p class="text-gray-600">
                            <span class="font-medium">{{ $bayi->nama_bayi }}</span> -
                            {{ $bayi->user->nama }}
                        </p>
                        <p class="text-sm text-yellow-600 mt-1 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Mode Lihat - Admin tidak dapat mengedit data
                        </p>
                    </div>
                </div>
            </div>

            <!-- Info Bayi Card -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-700">Informasi Bayi</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Jenis Kelamin</p>
                        <p class="font-medium">{{ $bayi->jenis_kelamin }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Lahir</p>
                        <p class="font-medium" x-text="formatDate('{{ $bayi->tanggal_lahir }}')"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Umur Sekarang</p>
                        <p class="font-medium" x-text="calculateAge('{{ $bayi->tanggal_lahir }}')"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Catatan</p>
                        <p class="font-medium" x-text="perkembangan.length + ' data'"></p>
                    </div>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Berat Badan Chart -->
                <div class="chart-container bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-2xl shadow-lg">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="bg-blue-500 rounded-lg p-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">Berat Badan</h2>
                    </div>
                    <div class="bg-white rounded-xl p-4 shadow-inner">
                        <canvas id="beratChart" height="300"></canvas>
                    </div>
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-600">Satuan: Kilogram (kg)</p>
                    </div>
                </div>

                <!-- Tinggi Badan Chart -->
                <div class="chart-container bg-gradient-to-br from-green-50 to-emerald-100 p-6 rounded-2xl shadow-lg">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="bg-green-500 rounded-lg p-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">Tinggi Badan</h2>
                    </div>
                    <div class="bg-white rounded-xl p-4 shadow-inner">
                        <canvas id="tinggiChart" height="300"></canvas>
                    </div>
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-600">Satuan: Sentimeter (cm)</p>
                    </div>
                </div>
            </div>

            <!-- Stats Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl p-5 text-center">
                    <p class="text-sm text-purple-700 font-medium mb-1">Total Pencatatan</p>
                    <p class="text-3xl font-bold text-purple-900" x-text="perkembangan.length"></p>
                </div>
                <div class="bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl p-5 text-center">
                    <p class="text-sm text-blue-700 font-medium mb-1">Berat Terkini</p>
                    <p class="text-3xl font-bold text-blue-900" x-text="latestWeight"></p>
                </div>
                <div class="bg-gradient-to-br from-green-100 to-green-200 rounded-xl p-5 text-center">
                    <p class="text-sm text-green-700 font-medium mb-1">Tinggi Terkini</p>
                    <p class="text-3xl font-bold text-green-900" x-text="latestHeight"></p>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-700">Riwayat Perkembangan</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-yellow-400 text-white">
                            <tr>
                                <th class="text-left py-3 px-4">No</th>
                                <th class="text-left py-3 px-4">Tanggal Catat</th>
                                <th class="text-left py-3 px-4">Umur (bulan)</th>
                                <th class="text-left py-3 px-4">Berat (kg)</th>
                                <th class="text-left py-3 px-4">Tinggi (cm)</th>
                                <th class="text-left py-3 px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-if="perkembangan.length === 0">
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-gray-500">
                                        Belum ada data perkembangan untuk bayi ini.
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(item, index) in perkembangan" :key="item.id">
                                <tr class="hover:bg-yellow-50 border-b">
                                    <td class="py-3 px-4" x-text="index + 1"></td>
                                    <td class="py-3 px-4" x-text="formatDate(item.tanggal_catat)"></td>
                                    <td class="py-3 px-4">
                                        <span x-text="item.umur_bulan"></span> bulan
                                    </td>
                                    <td class="py-3 px-4"
                                        x-text="(item.berat !== null && item.berat !== '') ? item.berat + ' kg' : '-'">
                                    </td>
                                    <td class="py-3 px-4"
                                        x-text="(item.tinggi !== null && item.tinggi !== '') ? item.tinggi + ' cm' : '-'">
                                    </td>
                                    <td class="py-3 px-4">
                                        <button @click="openDetailModal(item)"
                                            class="bg-purple-500 text-white px-3 py-1 rounded text-sm hover:bg-purple-600">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Detail Perkembangan -->
            <div x-cloak x-show="modalDetail" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50"
                @click.self="closeModal()" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90">

                <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden relative shadow-2xl">
                    <!-- Tombol Close -->
                    <button @click="closeModal()"
                        class="absolute top-4 right-4 z-10 text-gray-500 hover:text-red-600 text-2xl font-bold">
                        &times;
                    </button>

                    <div class="overflow-y-auto max-h-[90vh]">
                        <!-- Header -->
                        <div class="relative p-8 border-b">
                            <h2 class="text-3xl font-bold text-gray-800 text-center">{{ $bayi->nama_bayi }}</h2>
                            <p class="text-center text-gray-500 mt-1">Detail Perkembangan Bayi</p>
                        </div>

                        <!-- Content -->
                        <div class="p-8 space-y-6">
                            <!-- Info Cards Grid -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-purple-50 p-4 rounded-xl text-center border border-purple-200">
                                    <p class="text-xs text-purple-600 font-semibold mb-1">Tanggal Catat</p>
                                    <p class="text-lg font-bold text-purple-900"
                                        x-text="formatDate(detailData.tanggal_catat)"></p>
                                </div>
                                <div class="bg-pink-50 p-4 rounded-xl text-center border border-pink-200">
                                    <p class="text-xs text-pink-600 font-semibold mb-1">Umur Bayi</p>
                                    <p class="text-lg font-bold text-pink-900">
                                        <span x-text="detailData.umur_bulan"></span> bulan
                                    </p>
                                </div>
                            </div>

                            <!-- Pengukuran -->
                            <div
                                class="bg-gradient-to-r from-blue-50 to-cyan-50 p-6 rounded-xl border border-blue-200">
                                <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M3 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 2V5h1v1H5zM3 13a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3zm2 2v-1h1v1H5zM13 3a1 1 0 00-1 1v3a1 1 0 001 1h3a1 1 0 001-1V4a1 1 0 00-1-1h-3zm1 2v1h1V5h-1z"
                                            clip-rule="evenodd"></path>
                                        <path
                                            d="M11 4a1 1 0 10-2 0v1a1 1 0 002 0V4zM10 7a1 1 0 011 1v1h2a1 1 0 110 2h-3a1 1 0 01-1-1V8a1 1 0 011-1zM16 9a1 1 0 100 2 1 1 0 000-2zM9 13a1 1 0 011-1h1a1 1 0 110 2v2a1 1 0 11-2 0v-3zM7 11a1 1 0 100-2H4a1 1 0 100 2h3zM17 13a1 1 0 01-1 1h-2a1 1 0 110-2h2a1 1 0 011 1zM16 17a1 1 0 100-2h-3a1 1 0 100 2h3z">
                                        </path>
                                    </svg>
                                    Hasil Pengukuran
                                </h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div class="bg-white p-5 rounded-xl border-2 border-blue-300">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm text-gray-600">Berat Badan</span>
                                            <svg class="w-6 h-6 text-blue-500" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <p class="text-3xl font-bold text-blue-600"
                                            x-text="(detailData.berat !== null && detailData.berat !== '' && detailData.berat > 0) ? detailData.berat : '-'">
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">kilogram (kg)</p>
                                    </div>

                                    <div class="bg-white p-5 rounded-xl border-2 border-green-300">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm text-gray-600">Tinggi Badan</span>
                                            <svg class="w-6 h-6 text-green-500" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M12 1.586l-4 4v12.828l4-4V1.586zM3.707 3.293A1 1 0 002 4v10a1 1 0 00.293.707L6 18.414V5.586L3.707 3.293zM17.707 5.293L14 1.586v12.828l2.293 2.293A1 1 0 0018 16V6a1 1 0 00-.293-.707z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <p class="text-3xl font-bold text-green-600"
                                            x-text="(detailData.tinggi !== null && detailData.tinggi !== '' && detailData.tinggi > 0) ? detailData.tinggi : '-'">
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">sentimeter (cm)</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Tutup -->
                            <div class="flex justify-end pt-4">
                                <button @click="closeModal()"
                                    class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold transition-colors">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function perkembanganHandler() {
                    return {
                        sidebarOpen: false,
                        modalDetail: false,
                        perkembangan: @json($perkembanganBayi), // langsung isi dari controller
                        detailData: {},
                        latestWeight: '- kg',
                        latestHeight: '- cm',
                        chartBerat: null,
                        chartTinggi: null,
                        userId: {{ $bayi->user_id }},
                        bayiId: {{ $bayi->id }},

                        init() {
                            this.updateStats();
                            this.initCharts();
                        },

                        updateStats() {
                            if (this.perkembangan.length > 0) {
                                const latest = this.perkembangan[0];

                                // Paksa tampil '-' jika data null, undefined, atau string kosong
                                const berat = (latest.berat == null || latest.berat === '') ? '-' : latest.berat;
                                const tinggi = (latest.tinggi == null || latest.tinggi === '') ? '-' : latest.tinggi;

                                this.latestWeight = berat + (berat !== '-' ? ' kg' : '');
                                this.latestHeight = tinggi + (tinggi !== '-' ? ' cm' : '');
                            } else {
                                // Jika array perkembangan kosong total (bayi baru), tampilkan default
                                this.latestWeight = '- kg';
                                this.latestHeight = '- cm';
                            }
                        },

                        initCharts() {
                            // **PENTING: HANCURKAN CHART LAMA UNTUK MENGHINDARI ERROR "Canvas is already in use"**
                            if (this.beratChart) {
                                this.beratChart.destroy();
                            }
                            if (this.tinggiChart) {
                                this.tinggiChart.destroy();
                            }

                            // Sort data by tanggal ascending untuk grafik
                            const sortedData = [...this.perkembangan].sort((a, b) =>
                                new Date(a.tanggal_catat) - new Date(b.tanggal_catat)
                            );

                            const labels = sortedData.map(item => this.formatDateShort(item.tanggal_catat));
                            const beratData = sortedData.map(item => parseFloat(item.berat));
                            const tinggiData = sortedData.map(item => {
                                const val = parseFloat(item.tinggi);
                                return isNaN(val) ? undefined : val; // Mengabaikan titik data yang tidak valid
                            });

                            // ============= KONFIGURASI CHART BERAT =============
                            const chartConfigBerat = {
                                responsive: true,
                                maintainAspectRatio: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        padding: 12,
                                        titleFont: {
                                            size: 14,
                                            weight: 'bold'
                                        },
                                        bodyFont: {
                                            size: 13
                                        },
                                        cornerRadius: 8
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            color: 'rgba(0,0,0,0.05)',
                                            drawBorder: false
                                        },
                                        ticks: {
                                            font: {
                                                size: 12
                                            },
                                            color: '#6B7280'
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false,
                                            drawBorder: false
                                        },
                                        ticks: {
                                            font: {
                                                size: 12
                                            },
                                            color: '#6B7280'
                                        }
                                    }
                                }
                            };

                            // ============= KONFIGURASI CHART TINGGI (REVISI VISUALISASI DATA TUNGGAL) =============
                            const chartConfigTinggi = {
                                responsive: true,
                                maintainAspectRatio: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                        padding: 12,
                                        titleFont: {
                                            size: 14,
                                            weight: 'bold'
                                        },
                                        bodyFont: {
                                            size: 13
                                        },
                                        cornerRadius: 8
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        // Memastikan rentang Y cukup besar untuk melihat titik 80
                                        suggestedMax: 100,
                                        grid: {
                                            color: 'rgba(0,0,0,0.05)',
                                            drawBorder: false
                                        },
                                        ticks: {
                                            font: {
                                                size: 12
                                            },
                                            color: '#6B7280',
                                            stepSize: 10
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false,
                                            drawBorder: false
                                        },
                                        ticks: {
                                            font: {
                                                size: 12
                                            },
                                            color: '#6B7280'
                                        }
                                    }
                                }
                            };

                            const ctxBerat = document.getElementById('beratChart').getContext('2d');
                            this.chartBerat = new Chart(ctxBerat, {
                                type: 'line',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Berat (kg)',
                                        data: beratData,
                                        borderColor: '#3B82F6',
                                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                        borderWidth: 3,
                                        tension: 0.4,
                                        fill: true, // Ubah ke false agar fokus ke titik
                                        pointRadius: 6,
                                        pointHoverRadius: 10,
                                        pointHoverBackgroundColor: '#059669',
                                        pointHoverBorderColor: '#fff',
                                        pointHoverBorderWidth: 3,

                                        pointBackgroundColor: '#3B82F6',
                                        pointBorderColor: '#fff',
                                        pointBorderWidth: 3
                                    }]
                                },
                                options: chartConfigBerat
                            });

                            const ctxTinggi = document.getElementById('tinggiChart').getContext('2d');
                            this.chartTinggi = new Chart(ctxTinggi, {
                                type: 'line',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Tinggi Badan (cm)',
                                        data: tinggiData,
                                        borderColor: '#10B981',
                                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                        borderWidth: 3,
                                        tension: 0.4,
                                        fill: true, // Ubah ke false agar fokus ke titik
                                        pointRadius: 6, // DIBESARKAN
                                        pointHoverRadius: 10, // DIBESARKAN
                                        pointHoverBackgroundColor: '#059669',
                                        pointHoverBorderColor: '#fff',
                                        pointHoverBorderWidth: 3,

                                        pointBackgroundColor: '#10B981',
                                        pointBorderColor: '#fff',
                                        pointBorderWidth: 3 // DIPERTEBAL
                                    }]
                                },
                                options: chartConfigTinggi
                            });
                        },

                        // Fungsi updateCharts bisa dihapus, atau biarkan panggil initCharts() jika ada perubahan data dinamis
                        updateCharts() {
                            this.initCharts();
                        },

                        formatDate(dateString) {
                            if (!dateString) return '-';
                            const date = new Date(dateString);
                            return date.toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: 'long',
                                year: 'numeric'
                            });
                        },

                        formatDateShort(dateString) {
                            if (!dateString) return '-';
                            const date = new Date(dateString);
                            return date.toLocaleDateString('id-ID', {
                                day: 'numeric',
                                month: 'short',
                                year: 'numeric'
                            });
                        },

                        calculateAge(birthDate) {
                            if (!birthDate) return '-';
                            const today = new Date();
                            const birth = new Date(birthDate);
                            let months = today.getMonth() - birth.getMonth();
                            let years = today.getFullYear() - birth.getFullYear();

                            if (today.getDate() < birth.getDate()) months--;
                            if (months < 0) {
                                years--;
                                months += 12;
                            }

                            const totalMonths = years * 12 + months;
                            return years > 0 ?
                                `${years} tahun ${months} bulan (${totalMonths} bulan)` :
                                `${months} bulan`;
                        },

                        openDetailModal(item) {
                            this.detailData = item;
                            this.modalDetail = true;
                        },

                        closeModal() {
                            this.modalDetail = false;
                            this.detailData = {};
                        }
                    }
                }
            </script>
</body>

</html>
