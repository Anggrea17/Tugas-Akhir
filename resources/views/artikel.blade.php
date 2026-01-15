<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">
    <meta charset="UTF-8">
    <title>Artikel MPASI - GEMAS</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-amber-50 font-sans text-gray-800">
    <!-- Navbar untuk layar besar (desktop) -->
    <nav class="hidden md:flex bg-yellow-100 shadow p-4 justify-between items-center rounded-b-xl px-8"
        x-data="{ open: false, profileOpen: false }">
        <div class="flex items-center justify-between w-full md:w-auto">
            <a href="{{ route('landingpg') }}" class="flex items-center space-x-2"> <img
                    src="{{ asset('bahan/lognav.png') }}" alt="Logo" class="h-10"> </a>
        </div>

        <ul class="flex flex-row items-center space-x-6 font-medium">
            <li>
                @auth
                    <a href="{{ route('rekomendasi') }}"
                        class="text-black-600 font-semibold hover:text-yellow-600 transition-colors duration-200">
                        Rekomendasi MPASI
                    </button>
                @else
                    <a href="{{ route('login') }}"
                        class="text-black-600 font-semibold hover:text-yellow-600 transition-colors duration-200">
                        Rekomendasi MPASI
                    </a>
                @endauth
            </li>
            <!-- tampilan untuk halaman perkembangan bayi -->
            @auth
                <li>
                    <a href="{{ route('perkembangan.index') }}"
                        class="text-black-600 font-semibold hover:text-yellow-600 transition-colors duration-200">
                        Perkembangan Bayi
                    </a>
                </li>
            @endauth
            <li><a href="{{ route('artikel') }}" class="hover:text-yellow-600">Artikel</a></li>
            <!-- Tampilkan hanya untuk admin -->
            @auth
                @if (Auth::user()->role == 'admin')
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-yellow-600 font-semibold text-black">
                            Dashboard
                        </a>
                    </li>
                @endif
            @endauth

            @auth
                <!-- Profil User -->
                <li class="relative" x-cloak x-data="{ open: false }">
                    <button @click="open = !open" class="focus:outline-none">
                        <img src="{{ asset('bahan/pasangan.png') }}" alt="User"
                            class="w-9 h-9 rounded-full border-2 border-yellow-300">
                    </button>

                    <div x-show="open" x-cloak @click.away="open = false"
                        class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl text-gray-800 p-4 z-50"
                        x-transition>
                        <div class="flex items-center space-x-4">
                            <img src="{{ asset('bahan/pasangan.png') }}" alt="User" class="w-12 h-12 rounded-full">
                            <div>
                                <p class="font-semibold text-base">{{ Auth::user()->nama }}</p>
                                <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <!-- Edit Profil -->
                        <!-- Tombol Aksi -->
                        <div class="mt-4 flex justify-end gap-2">
                            <!-- Edit Profil -->
                            @if (Auth::check() && Auth::user()->role != 'admin')
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center gap-1 px-4 py-2 bg-indigo-500 text-white text-sm rounded-lg shadow hover:bg-indigo-600 transition whitespace-nowrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.232 5.232l3.536 3.536M9 11l6-6m2 2L9 17H7v-2l8-8z" />
                                    </svg>
                                    Edit Profile
                                </a>
                            @endif
                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-1 px-4 py-2 bg-red-500 text-white text-sm rounded-lg shadow hover:bg-red-600 transition whitespace-nowrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                </li>
            @else
                <li><a href="{{ route('register') }}" class="hover:text-yellow-600">Register</a></li>
                <li>
                    <a href="{{ route('login') }}"
                        class="bg-yellow-300 hover:bg-orange-400 text-white py-1 px-4 rounded-full">Login</a>
                </li>
            @endauth
        </ul>
    </nav>

    <!-- Bottom Navigation (Mobile Only) -->
    <div
        class="fixed bottom-0 left-0 right-0 z-50 bg-yellow-100 border-t border-yellow-300 text-brown-800 shadow md:hidden">
        <div class="flex justify-around py-2 text-sm">
            <a href="{{ route('landingpg') }}" class="flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg {{ request()->routeIs('landingpg') ? 'text-yellow-700' : 'text-gray-400' }}"
                    class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 9l9-7 9 7v11a2 2 0 01-2 2h-2a2 2 0 01-2-2V12H9v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                <span>Beranda</span>
            </a>
            @auth
                <a href="{{ route('rekomendasi') }}" class="flex flex-col items-center focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1 text-gray-700" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V7a1 1 0 00-1-1h-6l-2-2H5a1 1 0 00-1 1v6h16zM4 15h16v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2z" />
                    </svg> <span>Rekomendasi</span>
                </button>
            @else
                <a href="{{ route('login') }}" class="flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1 text-gray-700" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V7a1 1 0 00-1-1h-6l-2-2H5a1 1 0 00-1 1v6h16zM4 15h16v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2z" />
                    </svg> <span>Rekomendasi</span>
                </a>
            @endauth
            <!-- tampilan untuk halaman perkembangan bayi -->
            @auth
                <a href="{{ route('perkembangan.index') }}" class="flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-6a2 2 0 012-2h6M9 7l3 3m0 0l3-3m-3 3V3m5 18H5a2 2 0 01-2-2V5a2 2 0 012-2h4l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2z" />
                    </svg>
                    <span>Perkembangan Bayi</span>
                </a>
            @endauth
            <a href="{{ route('artikel') }}" class="flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21H5a2 2 0 01-2-2V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2z" />
                </svg>
                <span>Artikel</span>
            </a>
            <!-- Dashboard hanya muncul untuk admin -->
            @auth
                @if (Auth::user()->role == 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1 text-gray-700" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h18v4H3V3zm0 6h18v12H3V9zm4 3h4v6H7v-6z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                @endif
            @endauth

            @auth
                <div x-data="{ open: false }" class="relative flex flex-col items-center">
                    <button @click="open = !open" class="focus:outline-none flex flex-col items-center">
                        <img src="{{ asset('bahan/pasangan.png') }}" alt="User"
                            class="w-6 h-6 rounded-full border-2 border-yellow-300 mb-1">
                        <span class="text-xs sm:text-sm">Profil</span>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="fixed sm:absolute 
                   bottom-16 sm:bottom-full 
                   right-1 sm:right-0 
                   sm:mb-2 
                   w-80 sm:w-48 
                   bg-white rounded-lg shadow-xl text-gray-800 p-4 sm:p-3 z-50 text-left
                   max-w-[calc(100vw-0.5rem)] sm:max-w-none"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform translate-y-2 scale-95"
                        x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 transform translate-y-2 scale-95">

                        <!-- User Info Section -->
                        <div class="flex items-center space-x-3 mb-3">
                            <img src="{{ asset('bahan/pasangan.png') }}" alt="User"
                                class="w-12 h-12 sm:w-8 sm:h-8 rounded-full flex-shrink-0">
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-base sm:text-xs truncate text-gray-800">
                                    {{ Auth::user()->nama }}
                                </p>
                                <p class="text-sm sm:text-xs text-gray-500 truncate">
                                    {{ Auth::user()->email }}
                                </p>
                            </div>
                        </div>

                        <hr class="my-3 border-gray-200">

                        <!-- Action Buttons - Side by Side -->
                        <div class="flex gap-3 sm:flex-col sm:space-y-1 sm:gap-0">
                            <!-- Edit Profile Button -->
                            @if (Auth::check() && Auth::user()->role != 'admin')
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center justify-center gap-2 flex-1 sm:w-full px-4 py-3 sm:px-2 sm:py-1.5 
                           bg-indigo-500 text-white text-base sm:text-xs rounded-xl sm:rounded 
                           hover:bg-indigo-600 transition-colors duration-200
                           focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-3 sm:w-3 flex-shrink-0"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.232 5.232l3.536 3.536M9 11l6-6m2 2L9 17H7v-2l8-8z" />
                                    </svg>
                                    <span class="hidden sm:inline">Edit Profile</span>
                                    <span class="sm:hidden font-medium">Edit</span>
                                </a>
                            @endif
                            <!-- Logout Button -->
                            <form method="POST" action="{{ route('logout') }}" class="flex-1 sm:w-full">
                                @csrf
                                <button type="submit"
                                    class="flex items-center justify-center gap-2 w-full px-4 py-3 sm:px-2 sm:py-1.5 
                               bg-red-500 text-white text-base sm:text-xs rounded-xl sm:rounded 
                               hover:bg-red-600 transition-colors duration-200
                               focus:outline-none focus:ring-2 focus:ring-red-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-3 sm:w-3 flex-shrink-0"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span class="font-medium">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1h7a1 1 0 011 1v1" />
                </svg>
                <span>Login</span>
            </a>
        @endauth
    </div>
    </div>
    <!-- enc nav bar mobile -->

    <!-- Header -->
    <header class="bg-gradient-to-r from-yellow-100 to-orange-100 py-16">
        <div class="max-w-4xl mx-auto text-center px-4">
            <h1 class="text-4xl md:text-5xl font-bold text-yellow-900 mb-4">
                Artikel Edukatif MPASI
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Tips, panduan, dan informasi terpercaya seputar Makanan Pendamping Air Susu Ibu untuk si kecil
            </p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto py-12 px-4 mb-20 md:mb-0">

        <!-- Search and Filter Section -->
        <section class="mb-12">
            <form action="{{ route('artikel') }}" method="GET" class="space-y-4 md:space-y-0 md:flex md:gap-4">
                <!-- Search Input -->
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" name="search" placeholder="Cari artikel seputar MPASI..."
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl text-sm 
                                      focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent
                                      shadow-sm"
                            value="{{ $search }}">
                        <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="relative" x-data="{ open: false }">
                    <button type="button" @click="open = !open"
                        class="w-full md:w-auto bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 
                                   rounded-xl flex items-center justify-center gap-2 transition-colors shadow-sm">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.121A1 1 0 013 6.414V4z" />
                        </svg>
                        <span>Filter Kategori</span>
                    </button>

                    <!-- Category Dropdown -->
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-20"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95">

                        <!-- All Categories -->
                        <a href="{{ route('artikel', ['search' => $search]) }}"
                            class="block px-4 py-3 text-sm text-gray-700 hover:bg-yellow-50 transition-colors
                                  {{ empty(request('kategori')) ? 'bg-yellow-100 font-semibold text-yellow-800' : '' }}">
                            📚 Semua Kategori
                        </a>

                        <!-- Dynamic Categories -->
                        @foreach ($categories as $id => $nama)
                            <a href="{{ route('artikel', ['kategori' => $id]) }}"
                                class="block px-4 py-3 text-sm text-gray-700 hover:bg-yellow-50 transition-colors
                                  {{ request('kategori') == $id ? 'bg-yellow-100 font-semibold text-yellow-800' : '' }}">
                                {{ $nama }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </form>
        </section>

        <!-- Articles Grid -->
        <section class="space-y-6">
            @forelse ($artikels as $artikel)
                <article
                    class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-100">
                    <a href="{{ route('artikel.show', $artikel->slug) }}" class="block">
                        <div class="flex flex-col md:flex-row">
                            <!-- Article Image -->
                            <div
                                class="md:w-1/3 w-full h-48 md:h-auto overflow-hidden bg-gray-100 flex items-center justify-center">
                                @if ($artikel->gambar)
                                    <img src="{{ $artikel->gambar_url }}" alt="{{ $artikel->judul }}"
                                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <!-- Ikon default kalau tidak ada gambar -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 11l4 4 4-4m0 0l4 4m-8-4V3" />
                                        </svg>
                                        <span class="text-sm text-gray-500">Tidak ada gambar</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Article Content -->
                            <div class="md:w-2/3 w-full p-6 flex flex-col justify-between">
                                <div>
                                    <h2
                                        class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 hover:text-yellow-600 transition-colors">
                                        {{ $artikel->judul }}
                                    </h2>
                                    <p class="text-gray-600 text-sm leading-relaxed mb-4">
                                        {{ Str::limit(strip_tags($artikel->isi), 160) }}
                                    </p>
                                </div>

                                <!-- Article Meta -->
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <div class="flex items-center text-gray-500 text-sm">
                                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>{{ $artikel->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-500 text-sm">
                                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span>{{ $artikel->user->nama ?? 'Admin GEMAS' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </article>
            @empty
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3v3m0 0v3m0-3h3m-3 0h-3" />
                        </svg>

                        @if (!empty($search) || request('kategori'))
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Artikel tidak ditemukan</h3>
                            <p class="text-gray-500">Coba gunakan kata kunci atau kategori lain.</p>
                        @else
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada artikel</h3>
                            <p class="text-gray-500">Saat ini belum ada artikel yang tersedia.</p>
                        @endif

                    </div>
            @endforelse
        </section>

        <!-- Pagination -->
        @if ($artikels->hasPages())
            <div class="mt-12 flex justify-center">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    {{ $artikels->links('pagination::tailwind') }}
                </div>
            </div>
        @endif
    </main>
    <!-- footer -->
    <footer class="bg-white text-brown-800 py-10 px-6">
        <div class="max-w-6xl mx-auto grid md:grid-cols-3 gap-8">
            <!-- Tentang GEMAS -->
            <div>
                <img src="{{ asset('bahan/lognav.png') }}" alt="Ikon Artikel" />
            </div>
            <div>
                <p class="text-gray-700 text-base leading-relaxed text-justify">
                    GEMAS (Generasi Masa MPASI Sehat) adalah platform edukasi MPASI yang memberikan rekomendasi
                    Makanan Pendamping Air Susu Ibu berdasarkan kebutuhan kalori harian serta artikel seputar gizi &
                    MPASI.
                </p>
            </div>

            <!-- Menu Navigasi -->
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-6">Menu</h3>
                <ul class="text-sm space-y-2">
                       <li>
                        <a href="{{ route('landingpg') }}" class="text-base text-gray-700 hover:text-orange-600 transition-colors flex items-center group">
                            <svg class="w-4 h-4 mr-2 text-orange-500 opacity-0 group-hover:opacity-100 transform -translate-x-2 group-hover:translate-x-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span class="group-hover:translate-x-1 transition-transform">Beranda</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('rekomendasi') }}" class="text-base text-gray-700 hover:text-orange-600 transition-colors flex items-center group">
                            <svg class="w-4 h-4 mr-2 text-orange-500 opacity-0 group-hover:opacity-100 transform -translate-x-2 group-hover:translate-x-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span class="group-hover:translate-x-1 transition-transform">Rekomendasi MPASI</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('perkembangan.index') }}" class="text-base text-gray-700 hover:text-orange-600 transition-colors flex items-center group">
                            <svg class="w-4 h-4 mr-2 text-orange-500 opacity-0 group-hover:opacity-100 transform -translate-x-2 group-hover:translate-x-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span class="group-hover:translate-x-1 transition-transform">Perkembangan Bayi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('artikel') }}" class="text-base text-gray-700 hover:text-orange-600 transition-colors flex items-center group">
                            <svg class="w-4 h-4 mr-2 text-orange-500 opacity-0 group-hover:opacity-100 transform -translate-x-2 group-hover:translate-x-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span class="group-hover:translate-x-1 transition-transform">Artikel MPASI</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Garis dan Hak Cipta -->
        <div class="mt-8 text-center">
            <div class="mx-auto w-48 border-t-2 border-yellow-400"></div>
            <p class="text-base text-gray-600">
                &copy; 2025 GEMAS All rights reserved.
            </p>
        </div>
    </footer>
    <!-- end footer -->
</body>

</html>
