<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Resep - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen" x-data="{
    sidebarOpen: false,
    showAddModal: false,
    showViewModal: false,
    selectedMpasi: null,
    openModal: false,
    deleteUrl: '',
    mpasiToDelete: null
}" x-cloak x-init="@if (session('showAddModal')) $nextTick(() => { showAddModal = true }) @endif">
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
                    class="flex items-center px-4 py-2 text-gray-700 bg-yellow-100 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 mr-3 text-yellow-700">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    Kelola Resep MPASI
                </a>
                <a href="{{ route('admin.kelolaartikel') }}"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-yellow-100 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor"
                        class="w-5 h-5 mr-3 {{ request()->routeIs('admin.kelolaartikel') ? 'text-yellow-700' : 'text-gray-400' }}">
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

                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-xl text-gray-800 p-4 z-50">
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('bahan/admin-icon.png') }}" alt="Admin"
                            class="w-14 h-14 rounded-full">
                        <div>
                            <p class="font-semibold text-lg">{{ $admin->nama }}</p>
                            <p class="text-sm text-gray-500">{{ $admin->email }}</p>
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

        <div class="max-w-6xl mx-auto mt-8 px-4">
            <div class="flex flex-col md:flex-row justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-700">Kelola Resep MPASI</h2>

                <div class="flex items-center space-x-2 w-full md:w-auto">

                    <form method="GET" action="{{ route('admin.mpasi') }}"
                        class="flex items-center space-x-2 flex-1">
                        <div class="relative flex-1">
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400"
                                placeholder="Cari Resep...">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <img src="{{ asset('bahan/searchicon.png') }}" alt="Cari"
                                    class="w-5 h-5 opacity-60">
                            </div>
                        </div>

                        <div class="relative" x-data="{ open: false }">
                            <button type="button" @click="open = !open"
                                class="bg-yellow-500 text-white px-4 py-2 rounded-full hover:bg-yellow-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                                </svg>
                            </button>

                            <div x-show="open" x-cloak @click.away="open = false"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95">
                                <a href="{{ route('admin.mpasi', ['search' => $search]) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 @if (empty($kategori)) bg-yellow-200 font-semibold @endif">
                                    Semua Kategori
                                </a>
                                @foreach ($categories as $category)
                                    <a href="{{ route('admin.mpasi', ['search' => $search, 'kategori' => $category->id]) }}"
                                        class="block px-4 py-2 text-sm hover:bg-gray-100
                @if ($kategori == $category->id) bg-yellow-200 font-semibold @endif">
                                        {{ $category->nama_kategori }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </form>

                    <button @click="showAddModal = true" class="flex items-center gap-2 px-3 py-2 transition"
                        title="Tambah Resep">
                        <img src="{{ asset('bahan/plusicon.png') }}" alt="Tambah Artikel" class="w-8 h-8">
                    </button>
                </div>
            </div>

            <!-- notifikasi mpasi -->
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

            <div class="overflow-x-auto">
                <div x-data="{ open: false }" x-init="$el.classList.remove('hidden')" class="hidden">
                    <table class="min-w-full bg-white border border-gray-200 rounded shadow-sm">
                        <thead class="bg-yellow-400 text-white">
                            <tr>
                                <th class="text-left py-2 px-4 border-b">No</th>
                                <th class="text-left py-2 px-4 border-b">Nama Resep</th>
                                <th class="text-left py-2 px-4 border-b">Kategori</th>
                                <th class="text-left py-2 px-4 border-b">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mpasis as $mpasi)
                                <tr class="hover:bg-yellow-50">
                                    <td class="py-2 px-4 border-b">
                                        {{ $loop->iteration + ($mpasis->currentPage() - 1) * $mpasis->perPage() }}</td>
                                    <td class="py-2 px-4 border-b">{{ $mpasi->nama_menu }}</td>
                                    <td class="py-2 px-4 border-b">
                                        {{ $mpasi->kategori ? $mpasi->kategori->nama_kategori : '-' }}</td>
                                    <td class="py-2 px-4 border-b flex gap-2">

                                        <button type="button"
                                            @click="openModal = true; deleteUrl = '{{ route('admin.mpasi.delete', $mpasi->id) }}'; mpasiToDelete = { nama_menu: '{{ addslashes($mpasi->nama_menu) }}' }"
                                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                            Hapus
                                        </button>

                                        <a href="{{ route('admin.mpasi.edit', $mpasi->id) }}"
                                            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                            Edit
                                        </a>
                                        <button
                                            @click="
                                                showViewModal = true;
                                                selectedMpasi = {
                                                    ...{{ $mpasi->toJson() }},
                                                    gambar_url: '{{ asset('uploads/gambar_mpasi/' . $mpasi->gambar) }}'
                                                }
                                            "
                                            class="bg-lime-500 text-white px-3 py-1 rounded hover:bg-lime-600">
                                            Lihat
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-500">Belum ada resep yang
                                        tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-8">
                    {{ $mpasis->links('pagination::tailwind') }}
                </div>
            </div>

            <!-- Modal Tambah Resep -->
            <div x-show="showAddModal" x-cloak
                class="fixed inset-0 flex items-center justify-center bg-black/60 z-50">

                <div class="bg-white p-6 rounded-2xl w-full max-w-3xl max-h-[85vh] overflow-y-auto relative shadow-xl">
                    <!-- Tombol X -->
                    <button @click="showAddModal = false"
                        class="absolute top-4 right-4 text-gray-500 hover:text-red-600 text-2xl font-bold">&times;</button>

                    <h2 class="text-xl font-bold text-yellow-600 mb-4">Tambah Resep MPASI</h2>

                    <form action="{{ route('admin.mpasi.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-4">
                        @csrf

                        <!-- Nama Menu -->
                        <div>
                            <label class="block font-semibold">Nama Menu</label>
                            <input type="text" name="nama_menu" value="{{ old('nama_menu') }}"
                                class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500">
                            @error('nama_menu')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Umur -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium">Umur Minimal (bulan)</label>
                                <select name="min_umur"
                                    class="w-full border p-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500 rounded">
                                    <option value="">Pilih Usia</option>
                                    @for ($i = 6; $i <= 12; $i++)
                                        <option value="{{ $i }}"
                                            {{ old('min_umur') == $i ? 'selected' : '' }}>
                                            {{ $i }} Bulan
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium">Umur Maksimal (bulan)</label>
                                <select name="max_umur"
                                    class="w-full border p-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500 rounded">
                                    <option value="">Pilih Usia</option>
                                    @for ($i = 6; $i <= 12; $i++)
                                        <option value="{{ $i }}"
                                            {{ old('max_umur') == $i ? 'selected' : '' }}>
                                            {{ $i }} Bulan
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label class="block font-semibold">Kategori</label>
                            <select name="kategori_id"
                                class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $kat)
                                    <option value="{{ $kat->id }}"
                                        {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bahan Dinamis -->
                        <div x-data="{
                            bahans: {{ json_encode(old('bahan', [''])) }},
                            takarans: {{ json_encode(old('takaran', [''])) }}
                        }" class="space-y-2">

                            <label class="block font-semibold">Bahan-bahan</label>

                            <template x-for="(item, index) in bahans" :key="index">
                                <div class="flex gap-2 mb-2">
                                    <input type="text" :name="'bahan[' + index + ']'" x-model="bahans[index]"
                                        class="w-2/3 border rounded p-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                                        placeholder="Nama bahan">

                                    <input type="text" :name="'takaran[' + index + ']'" x-model="takarans[index]"
                                        class="w-1/3 border rounded p-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                                        placeholder="Takaran">

                                    <button type="button" @click="bahans.splice(index,1); takarans.splice(index,1)"
                                        class="px-2 text-red-500">✕</button>
                                </div>
                            </template>

                            <button type="button" @click="bahans.push(''); takarans.push('')"
                                class="px-3 py-1 bg-green-500 text-white rounded">+ Tambah Bahan</button>
                        </div>

                        <!-- Langkah Dinamis -->
                        <div x-data="{ steps: {{ json_encode(old('langkah', [''])) }} }" class="space-y-2">
                            <label class="block font-semibold">Langkah Pembuatan</label>

                            <template x-for="(step, index) in steps" :key="index">
                                <div class="flex gap-2 mb-2">
                                    <textarea :name="'langkah[' + index + ']'" x-model="steps[index]"
                                        class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                                        placeholder="Langkah..."></textarea>

                                    <button type="button" @click="steps.splice(index,1)"
                                        class="px-2 text-red-500">✕</button>
                                </div>
                            </template>

                            <button type="button" @click="steps.push('')"
                                class="px-3 py-1 bg-blue-500 text-white rounded">+ Tambah Langkah</button>
                        </div>

                        <!-- Porsi -->
                        <div x-data="{
                            porsi: '{{ old('porsi') <= 3 ? old('porsi', '1') : 'lainnya' }}',
                            customPorsi: '{{ old('porsi') > 3 ? old('porsi') : '' }}'
                        }" class="space-y-2">

                            <label class="block font-semibold mb-1">Porsi</label>

                            <select x-model="porsi"
                                class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                                :name="porsi === 'lainnya' ? '' : 'porsi'">

                                <option value="1">1 Porsi</option>
                                <option value="2">2 Porsi</option>
                                <option value="3">3 Porsi</option>
                                <option value="lainnya">Lainnya</option>
                            </select>

                            <template x-if="porsi === 'lainnya'">
                                <input type="number" name="porsi" x-model="customPorsi"
                                    class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                                    placeholder="Masukkan jumlah porsi">
                            </template>
                        </div>

                        <!-- Gizi -->
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block font-medium">Energi (kkal)</label>
                                <input type="number" step="0.01" name="energi" value="{{ old('energi') }}"
                                    class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500">
                            </div>
                            <div>
                                <label class="block font-medium">Karbohidrat (g)</label>
                                <input type="number" step="0.01" name="karbohidrat"
                                    value="{{ old('karbohidrat') }}"
                                    class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500">
                            </div>
                            <div>
                                <label class="block font-medium">Protein (g)</label>
                                <input type="number" step="0.01" name="protein" value="{{ old('protein') }}"
                                    class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500">
                            </div>
                            <div>
                                <label class="block font-medium">Lemak (g)</label>
                                <input type="number" step="0.01" name="lemak" value="{{ old('lemak') }}"
                                    class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500">
                            </div>
                            <div>
                                <label class="block font-medium">Zat Besi (mg)</label>
                                <input type="number" step="0.01" name="zat_besi" value="{{ old('zat_besi') }}"
                                    class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500">
                            </div>
                        </div>

                        <!-- Upload Gambar -->
                        <div>
                            <label class="block font-semibold mb-2 text-gray-700">Gambar Menu</label>

                            <input type="file" name="gambar" id="gambar" accept="image/*"
                                class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm 
                           focus:ring focus:ring-yellow-200 focus:border-yellow-400 p-2">

                            <!-- Error realtime -->
                            <p id="errorGambar" class="text-red-500 text-sm mt-1 hidden"></p>

                            <!-- Preview -->
                            <img id="previewGambar" class="hidden w-32 h-32 mt-3 rounded-lg border object-cover">

                            @error('gambar')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tombol -->
                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" @click="showAddModal = false"
                                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                            <button type="submit"
                                class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MODAL PREVIEW MPASI -->
            <div x-show="showViewModal" x-cloak
                class="fixed inset-0 flex items-center justify-center bg-black/60 z-50"
                @click.self="showViewModal = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90">

                <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden relative shadow-2xl">
                    <!-- Tombol Close -->
                    <button @click="showViewModal = false"
                        class="absolute top-4 right-4 z-10 text-gray-500 hover:text-red-600 text-2xl font-bold">
                        &times;
                    </button>

                    <div class="overflow-y-auto max-h-[90vh]">
                        <!-- Header dengan Gambar -->
                        <div class="relative p-8">
                            <template x-if="selectedMpasi?.gambar">
                                <div class="flex justify-center mb-4">
                                    <img :src="'/' + selectedMpasi.gambar" alt="gambar mpasi"
                                        class="w-64 h-64 object-cover rounded-2xl shadow-2xl border-4 border-white">
                                </div>
                            </template>
                            <h2 class="text-3xl font-bold text-gray-800 text-center"
                                x-text="selectedMpasi?.nama_menu"></h2>
                        </div>

                        <!-- Content -->
                        <div class="p-8 space-y-6">
                            <!-- Info Cards Grid -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="bg-blue-50 p-4 rounded-xl text-center border border-blue-200">
                                    <p class="text-xs text-blue-600 font-semibold mb-1">Porsi</p>
                                    <p class="text-lg font-bold text-blue-900" x-text="selectedMpasi?.porsi"></p>
                                </div>
                                <div class="bg-purple-50 p-4 rounded-xl text-center border border-purple-200">
                                    <p class="text-xs text-purple-600 font-semibold mb-1">Kategori</p>
                                    <p class="text-lg font-bold text-purple-900"
                                        x-text="selectedMpasi?.kategori?.nama_kategori"></p>
                                </div>
                                <div class="bg-green-50 p-4 rounded-xl text-center border border-green-200">
                                    <p class="text-xs text-green-600 font-semibold mb-1">Usia Min</p>
                                    <p class="text-lg font-bold text-green-900"><span
                                            x-text="selectedMpasi?.min_umur"></span> bln</p>
                                </div>
                                <div class="bg-pink-50 p-4 rounded-xl text-center border border-pink-200">
                                    <p class="text-xs text-pink-600 font-semibold mb-1">Usia Max</p>
                                    <p class="text-lg font-bold text-pink-900"><span
                                            x-text="selectedMpasi?.max_umur"></span> bln</p>
                                </div>
                            </div>

                            <!-- Kandungan Gizi -->
                            <div
                                class="bg-gradient-to-r from-orange-50 to-yellow-50 p-6 rounded-xl border border-orange-200">
                                <h3 class="text-lg font-bold text-orange-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                        <path fill-rule="evenodd"
                                            d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Kandungan Gizi per Porsi
                                </h3>
                                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-orange-600"
                                            x-text="selectedMpasi?.energi > 0 ? selectedMpasi?.energi : '-'"></p>
                                        <p class="text-xs text-gray-600">Energi (kkal)</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-orange-600"
                                            x-text="selectedMpasi?.karbohidrat > 0 ? selectedMpasi?.karbohidrat : '-'">
                                        </p>
                                        <p class="text-xs text-gray-600">Karbohidrat (g)</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-orange-600"
                                            x-text="selectedMpasi?.protein > 0 ? selectedMpasi?.protein : '-'"></p>
                                        <p class="text-xs text-gray-600">Protein (g)</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-orange-600"
                                            x-text="selectedMpasi?.lemak > 0 ? selectedMpasi?.lemak : '-'"></p>
                                        <p class="text-xs text-gray-600">Lemak (g)</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-orange-600"
                                            x-text="selectedMpasi?.zat_besi > 0 ? selectedMpasi?.zat_besi : '-'"></p>
                                        <p class="text-xs text-gray-600">Zat Besi (mg)</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Bahan & Langkah -->
                            <div class="grid md:grid-cols-2 gap-6">
                                <!-- Bahan -->
                                <div class="bg-white border-2 border-yellow-300 rounded-xl p-6 shadow-lg">
                                    <h3 class="text-lg font-bold text-yellow-800 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z">
                                            </path>
                                        </svg>
                                        Bahan-Bahan
                                    </h3>
                                    <ul class="space-y-2">
                                        <template x-for="(b, i) in selectedMpasi?.bahans" :key="i">
                                            <li class="flex items-start">
                                                <span
                                                    class="inline-block w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                                <span class="text-gray-700"
                                                    x-text="(b.takaran ? b.takaran + ' ' : '') + b.bahan"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>

                                <!-- Langkah -->
                                <div class="bg-white border-2 border-green-300 rounded-xl p-6 shadow-lg">
                                    <h3 class="text-lg font-bold text-green-800 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Cara Membuat
                                    </h3>
                                    <ol class="space-y-3">
                                        <template x-for="(l, i) in selectedMpasi?.langkahs" :key="i">
                                            <li class="flex items-start">
                                                <span
                                                    class="inline-flex items-center justify-center w-6 h-6 bg-green-500 text-white text-xs font-bold rounded-full mr-3 flex-shrink-0 mt-0.5"
                                                    x-text="i + 1"></span>
                                                <span class="text-gray-700" x-text="l.langkah"></span>
                                            </li>
                                        </template>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- MODAL DELETE MPASI -->
            <div x-show="openModal" x-cloak x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto">

                <!-- Background overlay -->
                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

                <!-- Modal container -->
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div x-show="openModal" x-transition:enter="transition ease-out duration-300"
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
                                        Hapus Resep MPASI
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Apakah Anda yakin ingin menghapus resep
                                            <span class="font-medium text-gray-700"
                                                x-text="mpasiToDelete?.nama_menu"></span>?
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal buttons -->
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <form :action="deleteUrl" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                                    Hapus
                                </button>
                            </form>
                            <button type="button" @click="openModal = false; mpasiToDelete = null"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    document.getElementById('gambar').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('previewGambar');
        const error = document.getElementById('errorGambar');

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

        // ❌ lebih dari 2 MB
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

</html>
