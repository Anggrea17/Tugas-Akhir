<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">
    <meta charset="UTF-8">
    <title>Kelola Artikel - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen" x-cloak x-data="{ showAddModal: false, sidebarOpen: false, modalHapus: false, hapusUrl: '', artikelToDelete: null }">

    <!-- Sidebar -->
    <aside
        class="fixed z-30 inset-y-0 left-0 w-64 bg-white shadow-md h-screen transform md:translate-x-0 transition-transform duration-200 ease-in-out"
        :class="{ '-translate-x-full': !sidebarOpen }">
        <div class="p-6">
            <img src="{{ asset('bahan/lognav.png') }}" alt="Ikon Artikel" />
            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-yellow-100 rounded ">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-yellow-700' : 'text-gray-400' }}"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.users') }}"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-yellow-100 rounded">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.users') ? 'text-yellow-700' : 'text-gray-400' }}"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path
                            d="M5.121 17.804A4 4 0 017.757 16h8.486a4 4 0 012.636 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                        </path>
                    </svg>
                    Kelola User
                </a>
                <a href="{{ route('admin.mpasi') }}"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-yellow-100 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor"
                        class="w-5 h-5 mr-3 {{ request()->routeIs('admin.mpasi') ? 'text-yellow-700' : 'text-gray-400' }}">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    Kelola Resep MPASI
                </a>
                <a href="{{ route('admin.kelolaartikel') }}"
                    class="flex items-center px-4 py-2 text-gray-700 bg-yellow-100 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 mr-3 text-yellow-700">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                    Kelola Artikel
                </a>
                <a href="{{ route('admin.kategoriartikel') }}"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-yellow-100 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 mr-3 {{ request()->routeIs('admin.kategoriartikel') ? 'text-yellow-700' : 'text-gray-400' }}"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.53 16.122a3 3 0 0 0-5.716 0A9.754 9.754 0 0 0 3 18.375v2.25H21v-2.25a9.754 9.754 0 0 0-1.584-2.253m-11.397-3.263l2.559 2.559c.351.351.86.643 1.41.874m-2.288-3.328c.847.476 1.765.873 2.73 1.148M3 14.166V5.25a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 5.25v8.916m-16.142 6.642v.002v.002a.75.75 0 0 0-.75.75v.002a.75.75 0 0 0 .75.75H21v-.002a.75.75 0 0 0-.75-.75h-15.358Z" />
                    </svg>
                    Kelola Kategori Artikel
                </a>
                <a href="{{ route('admin.kategorimpasi') }}"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-yellow-100 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 mr-3 {{ request()->routeIs('admin.kategorimpasi') ? 'text-yellow-700' : 'text-gray-400' }}"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
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
            @php
                use Illuminate\Support\Facades\Auth;
                $admin = Auth::user();
            @endphp

            <div class="relative ml-auto" x-cloak x-data="{ open: false }">
                <button @click="open = !open" class="focus:outline-none">
                    <img src="{{ asset('bahan/admin-icon.png') }}" alt="Admin"
                        class="w-10 h-10 rounded-full border-2 border-white shadow">
                </button>

                <div x-show="open" x-cloak @click.away="open = false"
                    class="absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-xl text-gray-800 p-4 z-50">
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('bahan/admin-icon.png') }}" alt="Admin"
                            class="w-14 h-14 rounded-full">
                        <div>
                            <p class="font-semibold text-lg">{{ $admin->nama }}</p>
                            <p class="text-sm text-gray-500">{{ $admin->email }}</p>
                        </div>
                    </div>
                    <!-- Tombol Logout -->
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
            <div x-data="{ openView: false, viewArtikel: {} }">
                <div class="flex flex-col md:flex-row justify-between items-center mb-4">
                    <h2 class="text-2xl font-semibold text-gray-700">Kelola Artikel</h2>
                    <div class="flex items-center space-x-2  w-full md:w-auto">
                        <!-- Form Pencarian -->
                        <form method="GET" action="{{ route('admin.kelolaartikel') }}"
                            class="flex items-center space-x-2 flex-1">
                            <div class="relative flex-1">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400"
                                    placeholder="Cari Artikel...">
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                    <img src="{{ asset('bahan/searchicon.png') }}" alt="Cari"
                                        class="w-5 h-5 opacity-60">
                                </div>
                            </div>
                            <!-- Tombol filter  -->
                            <div class="relative" x-data="{ open: false }">
                                <button type="button" @click="open = !open"
                                    class="bg-yellow-500 text-white px-4 py-2 rounded-full hover:bg-yellow-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                                    </svg>
                                </button>
                                <!-- Dropdown Kategori -->
                                <div x-show="open" x-cloak @click.away="open = false"
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95">
                                    <a href="{{ route('admin.kelolaartikel', ['search' => $search]) }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 @if (empty($kategori)) bg-yellow-200 font-semibold @endif">
                                        Semua Kategori
                                    </a>
                                    @foreach ($categories as $category)
                                        <a href="{{ route('admin.kelolaartikel', ['search' => $search, 'kategori' => $category->id]) }}"
                                            class="block px-4 py-2 text-sm hover:bg-gray-100
                @if ($kategori == $category->id) bg-yellow-200 font-semibold @endif">
                                            {{ $category->nama_kategori }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </form>
                        <!-- Tombol Tambah Artikel -->
                        <button @click="showAddModal = true" class="flex items-center gap-2 px-3 py-2 transition">
                            <img src="{{ asset('bahan/plusicon.png') }}" alt="Tambah Artikel" class="w-8 h-8">
                        </button>
                    </div>
                </div>
                <!-- notifikasi Artikel -->
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-500"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-2"
                        class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 shadow">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-500"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-2"
                        class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 shadow">
                        {{ session('error') }}
                    </div>
                @endif
                <!-- Daftar error global -->
                @if ($errors->any())
                    <div x-data="{ show: true }" x-init="setTimeout(() => {
                        show = false; // 1. Sembunyikan error setelah 5 detik
                        setTimeout(() => showAddModal = true, 300) // 2. Buka modal lagi setelah 300ms (delay kecil untuk transisi smooth)
                    }, 5000)" x-show="show" x-transition
                        class="bg-red-100 text-red-700 px-4 py-2 rounded shadow-md">
                        <ul class="list-disc ml-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- Tabel Artikel -->
                <div x-data="{ openModal: false, selectedArtikel: null }">
                    <div x-data="{ modalHapus: false, hapusUrl: '' }">
                        <div class="overflow-x-auto">
                            <table class="min-w-[800px] w-full bg-white border border-gray-300 rounded-lg shadow">
                                <thead>
                                    <tr class="bg-yellow-400 text-white text-left">
                                        <th class="px-4 py-2 font-semibold">No</th>
                                        <th class="px-4 py-2 font-semibold">Judul</th>
                                        <th class="px-4 py-2 font-semibold">Kategori</th>
                                        <th class="px-4 py-2 font-semibold">Tanggal Posting</th>
                                        <th class="px-4 py-2 font-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($artikels as $artikel)
                                        <tr class="border-t hover:bg-yellow-50">
                                            <td class="px-4 py-2">
                                                {{ $loop->iteration + ($artikels->currentPage() - 1) * $artikels->perPage() }}
                                            </td>
                                            <td class="px-4 py-2">{{ $artikel->judul }}</td>
                                            <td class="px-4 py-2">
                                                {{ $artikel->kategori->nama_kategori ?? 'Tidak Ada Kategori' }}</td>
                                            <td class="px-4 py-2">
                                                {{ \Carbon\Carbon::parse($artikel->tanggal_post)->translatedFormat('d F Y') }}
                                            </td>
                                            <td class="px-4 py-2 space-x-2 flex ">
                                                <button
                                                    @click="modalHapus = true; hapusUrl = '{{ route('admin.kelolaartikel.delete', $artikel->id) }}'; artikelToDelete = { judul: '{{ addslashes($artikel->judul) }}' }"
                                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 inline-block">
                                                    Hapus
                                                </button>
                                                <a href="{{ route('admin.kelolaartikel.edit', $artikel->id) }}"
                                                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 inline-block">
                                                    Edit
                                                </a>
                                                <button
                                                    @click="
        openModal = true; 
        selectedArtikel = {
            ...{{ $artikel->toJson() }},
            gambar: '{{ $artikel->gambar ? asset($artikel->gambar) : '' }}'
        }
    "
                                                    class="bg-lime-500 text-white px-3 py-1 rounded hover:bg-lime-600">
                                                    Lihat
                                                </button>

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-gray-500 py-4">
                                                Belum ada artikel yang tersedia.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- end tabel artikel -->

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $artikels->links('pagination::tailwind') }}
                        </div>

                        <!-- Modal Preview Artikel -->
                        <div x-show="openModal" x-cloak
                            class="fixed inset-0 flex items-center justify-center bg-black/60 z-50"
                            @click.self="openModal = false" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-90"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-90">

                            <div
                                class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden relative shadow-2xl">
                                <!-- Tombol Close -->
                                <button @click="openModal = false"
                                    class="absolute top-4 right-4 z-10 text-gray-500 hover:text-red-600 text-2xl font-bold">
                                    &times;
                                </button>

                                <div class="overflow-y-auto max-h-[90vh]">
                                    <!-- Header -->
                                    <div class="relative p-8 border-b">
                                        <h2 class="text-3xl font-bold text-gray-800 mb-3"
                                            x-text="selectedArtikel?.judul"></h2>

                                        <!-- Meta Info -->
                                        <div class="flex flex-wrap items-center gap-3 text-sm">
                                            <span
                                                class="px-3 py-1.5 bg-gradient-to-r from-yellow-400 to-orange-400 text-white rounded-full font-semibold shadow-sm"
                                                x-text="selectedArtikel?.kategori?.nama_kategori || 'Tanpa Kategori'"></span>

                                            <div class="flex items-center gap-2 text-gray-500">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                <span
                                                    x-text="new Date(selectedArtikel?.tanggal_post).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })"></span>
                                            </div>

                                            <template x-if="selectedArtikel?.sumber">
                                                <div class="flex items-center gap-2 text-gray-500">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span>Sumber: <span x-text="selectedArtikel?.sumber"></span></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-8 space-y-6">
                                        <!-- Gambar Artikel -->
                                        <template x-if="selectedArtikel?.gambar">
                                            <div class="flex justify-center">
                                                <img :src="selectedArtikel.gambar" alt="gambar artikel"
                                                    class="w-full max-w-3xl max-h-[500px] object-cover rounded-xl shadow-lg">
                                            </div>
                                        </template>

                                        <!-- Isi Artikel -->
                                        <div
                                            class="bg-gradient-to-r from-orange-50 to-yellow-50 p-6 rounded-xl border border-orange-200">
                                            <div class="trix-content prose prose-yellow max-w-none leading-relaxed"
                                                style="text-align: justify; text-justify: inter-word;"
                                                x-html="selectedArtikel?.isi">
                                            </div>
                                        </div>

                                        <!-- Tombol Tutup -->
                                        <div class="flex justify-end pt-4">
                                            <button @click="openModal = false"
                                                class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold transition-colors">
                                                Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Tambah Artikel -->
                        <div x-show="showAddModal" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95" x-cloak
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">

                            <div
                                class="bg-white p-6 rounded-2xl w-full max-w-3xl max-h-[85vh] overflow-y-auto relative shadow-xl">
                                <button @click="showAddModal = false"
                                    class="absolute top-4 right-4 text-gray-500 hover:text-red-600 text-2xl font-bold">&times;</button>

                                <h2 class="text-lg text-yellow-600 font-semibold mb-4">Tambah Artikel</h2>

                                <form method="POST" action="{{ route('admin.kelolaartikel.store') }}"
                                    enctype="multipart/form-data" onsubmit="localStorage.removeItem('draftKonten')">
                                    @csrf

                                    <!-- Judul -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul
                                            Artikel</label>
                                        <input type="text" name="judul" id="judul"
                                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                                            placeholder="Masukkan judul artikel" value="{{ old('judul') }}">
                                        @error('judul')
                                            <p class="text-red-500 text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Kategori Artikel -->
                                    <div class="mb-4">
                                        <label for="kategori"
                                            class="block text-sm font-medium text-gray-700 mb-1">Kategori
                                            Artikel</label>
                                        <select name="kategori_id" id="kategori"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                            <option value="" disabled>Pilih kategori...</option>

                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('kategori_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kategori_id')
                                            <p class="text-red-500 text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Sumber Artikel -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Sumber
                                            Artikel</label>
                                        <input type="text" name="sumber" id="sumber"
                                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                                            placeholder="Masukkan sumber" value="{{ old('sumber') }}">
                                        @error('sumber')
                                            <p class="text-red-500 text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Gambar Artikel -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Gambar Artikel
                                        </label>

                                        <input type="file" name="gambar" id="gambarArtikel" accept="image/*"
                                            class="w-full border border-gray-300 rounded px-3 py-2 
               focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500">

                                        <!-- Error realtime -->
                                        <p id="errorGambarArtikel" class="text-red-500 text-sm mt-1 hidden"></p>

                                        <!-- Preview -->
                                        <img id="previewGambarArtikel"
                                            class="hidden w-32 h-32 mt-3 rounded border object-cover">

                                        {{-- Error backend --}}
                                        @error('gambar')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Konten Artikel -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Konten
                                            Artikel</label>

                                        <!-- Hidden input -->
                                        <input id="isi" type="hidden" name="isi"
                                            value="{{ old('isi') }}">

                                        <!-- Trix editor -->
                                        <trix-editor input="isi"
                                            class="trix-content max-h-64 overflow-y-auto border rounded p-2"></trix-editor>
                                        <p class="text-sm text-gray-500 mt-2" id="estimasiWaktuBaca">Estimasi waktu
                                            baca: -</p>

                                        @error('isi')
                                            <p class="text-red-500 text-sm">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Tombol Batal & Simpan -->
                                    <div class="flex justify-end gap-2 mt-4">
                                        <button type="button" @click="showAddModal = false"
                                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Restore Trix old() -->
                        <script>
                            document.addEventListener("trix-initialize", function() {
                                const oldIsi = `{!! old('isi') !!}`;
                                if (oldIsi) {
                                    document.querySelector("trix-editor").editor.loadHTML(oldIsi);
                                }
                            });
                        </script>

                        <!-- Modal Konfirmasi Hapus Artikel -->
                        <div x-show="modalHapus" x-cloak x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-50 overflow-y-auto">

                            <!-- Background overlay -->
                            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

                            <!-- Modal container -->
                            <div
                                class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
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
                                                    Hapus Artikel
                                                </h3>
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-500">
                                                        Apakah Anda yakin ingin menghapus artikel
                                                        <span class="font-medium text-gray-700"
                                                            x-text="artikelToDelete?.judul"></span>?
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal buttons -->
                                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                        <form x-bind:action="hapusUrl" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                                                Hapus
                                            </button>
                                        </form>
                                        <button type="button" @click="modalHapus = false; artikelToDelete = null"
                                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                            Batal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>

                <!-- Script Trix + LocalStorage -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const kontenInput = document.querySelector("input#isi");
                        const trixEditor = document.querySelector("trix-editor");
                        const estimasiEl = document.getElementById("estimasiWaktuBaca");

                        // Restore dari localStorage
                        const saved = localStorage.getItem("draftKonten");
                        if (saved && kontenInput && trixEditor) {
                            kontenInput.value = saved;
                            trixEditor.editor.loadHTML(saved);
                        }

                        // Hitung Estimasi & Simpan Draft
                        document.addEventListener("trix-change", function(event) {
                            const plainText = event.target.editor.getDocument().toString().trim();
                            localStorage.setItem("draftKonten", plainText);
                            const wordCount = plainText ? plainText.split(/\s+/).length : 0;
                            const waktu = Math.max(1, Math.ceil(wordCount / 200));
                            estimasiEl.textContent = `Estimasi waktu baca: ${waktu} menit (${wordCount} kata)`;
                        });
                    });
                </script>
                <script>
                    document.addEventListener("trix-attachment-add", function(event) {
                        const attachment = event.attachment;

                        // Hapus caption otomatis
                        setTimeout(() => {
                            const caption = attachment.getAttachmentElement().querySelector("figcaption");
                            if (caption) caption.remove();
                        }, 100);

                        // Kalau yang di-attach berupa file, upload ke server
                        if (attachment.file) {
                            uploadFileAttachment(attachment);
                        }
                    });

                    function uploadFileAttachment(attachment) {
                        const file = attachment.file;
                        const form = new FormData();
                        form.append("file", file);

                        fetch("{{ route('admin.upload') }}", {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: form
                            })
                            .then(res => res.json())
                            .then(data => {
                                // Pastikan response JSON: { "url": "http://..." }
                                attachment.setAttributes({
                                    url: data.url,
                                    href: data.url
                                });
                                attachment.setUploadProgress(100);
                            });
                    }
                </script>
                <script>
                    document.getElementById("artikelForm").addEventListener("submit", function(e) {
                        let sumber = document.getElementById("sumber").value.trim();
                        let notif = document.getElementById("notifSumber");

                        if (sumber === "") {
                            e.preventDefault(); // stop submit
                            notif.classList.remove("hidden"); // tampilkan notif merah
                        } else {
                            notif.classList.add("hidden");
                        }
                        // Estimasi waktu baca
                        function updateEstimasi(content) {
                            let plainText = content.trim();
                            let wordCount = plainText ? plainText.split(/\s+/).length : 0;
                            let minutes = Math.max(1, Math.ceil(wordCount / 200));

                            document.getElementById("estimasiWaktuBaca").textContent =
                                `Estimasi waktu baca: ${minutes} menit (${wordCount} kata)`;
                        }

                    });
                </script>

                <!-- style untuk trix versi 2 -->
                <style>
                    /* Untuk unordered list (bullet) */
                    .trix-content ul {
                        list-style-type: disc;
                        margin-left: 1.5rem;
                        padding-left: 1rem;
                    }

                    .trix-content ul>li {
                        margin: 0.25rem 0;
                    }

                    /* Untuk ordered list (angka) dengan CSS counter */
                    .trix-content ol {
                        counter-reset: item;
                        margin-left: 1.5rem;
                        padding-left: 1.5rem;
                    }

                    .trix-content ol>li {
                        display: block;
                        position: relative;
                        margin: 0.25rem 0;
                    }

                    .trix-content ol>li:before {
                        content: counters(item, ".") ". ";
                        counter-increment: item;
                        position: absolute;
                        left: -1.5rem;
                    }
                </style>
                <style>
                    /* Semua gambar dalam Trix ditengah */
                    trix-editor .attachment {
                        display: flex;
                        justify-content: center;
                    }

                    trix-editor figure {
                        display: flex !important;
                        justify-content: center !important;
                    }
                </style>
                <script>
                    document.getElementById('gambarArtikel').addEventListener('change', function(event) {
                        const file = event.target.files[0];
                        const preview = document.getElementById('previewGambarArtikel');
                        const error = document.getElementById('errorGambarArtikel');

                        // reset
                        error.classList.add('hidden');
                        error.textContent = '';
                        preview.classList.add('hidden');
                        preview.src = '';

                        if (!file) return;

                        const sizeMB = (file.size / 1024 / 1024).toFixed(2);

                        // ❌ bukan gambar
                        if (!file.type.startsWith('image/')) {
                            error.textContent = 'File yang dipilih harus berupa gambar.';
                            error.classList.remove('hidden');

                            event.target.value = '';
                            setTimeout(() => error.classList.add('hidden'), 4000);
                            return;
                        }

                        // ❌ ukuran > 2 MB
                        if (file.size > 2 * 1024 * 1024) {
                            error.textContent = `Ukuran gambar ${sizeMB} MB, maksimal 2 MB`;
                            error.classList.remove('hidden');

                            event.target.value = '';
                            setTimeout(() => error.classList.add('hidden'), 4000);
                            return;
                        }

                        // ✅ valid → preview
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            preview.classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    });
                </script>

                <script>
                    //modal hapus
                    // Update Alpine.js data
                    document.addEventListener('alpine:init', () => {
                        Alpine.data('artikelData', () => ({
                            modalHapus: false,
                            hapusUrl: '',
                            artikelToDelete: null,

                            openHapusModal(url, judulArtikel) {
                                this.hapusUrl = url;
                                this.artikelToDelete = {
                                    judul: judulArtikel
                                };
                                this.modalHapus = true;
                            }
                        }));
                    });
                </script>

</body>

</html>
