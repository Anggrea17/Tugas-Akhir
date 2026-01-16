<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GEMAS - Generasi Masa MPASI Sehat</title>
    @include('google-analytics') <!-- Panggil file GA di sini -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-yellow-50 text-brown-800">

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
                    <button @click="$store.bayiData.goToRekomendasi()"
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
                            @unless (Auth::user()->role === 'admin')
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center gap-1 px-4 py-2 bg-indigo-500 text-white text-sm rounded-lg shadow hover:bg-indigo-600 transition whitespace-nowrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.232 5.232l3.536 3.536M9 11l6-6m2 2L9 17H7v-2l8-8z" />
                                    </svg>
                                    Edit Profile
                                </a>
                            @endunless
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
                    </div>
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
                <button onclick="mobileGoToRekomendasi()" class="flex flex-col items-center focus:outline-none">
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
                            @unless (Auth::user()->role === 'admin')
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
                            @endunless

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
    <!-- Hero Section -->
    <section
        class="relative bg-gradient-to-br from-yellow-50 via-yellow-100 to-yellow-50 animate-gradient-dreamy overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center px-8 py-12 relative z-10">
            <!-- Text content -->

            <div>
                <div class="inline-block">
                    <span class="bg-yellow-200 text-yellow-900 text-xs font-semibold px-4 py-2 rounded-full shadow-sm">
                        ✨ Nutrisi Terbaik untuk Si Kecil
                    </span>
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight text-gray-900">
                    Rekomendasi
                    <span
                        class="bg-gradient-to-r  from-yellow-500 via-orange-500 to-red-500 bg-clip-text text-transparent">
                        MPASI
                    </span>
                    Berdasarkan Kalori Bayi
                </h1>
                <p class="text-lg md:text-xl text-gray-700 leading-relaxed">
                    Dapatkan rekomendasi MPASI yang sehat, bergizi, dan disesuaikan dengan kebutuhan kalori harian bayi
                    Anda secara personal.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    @auth
                        <button @click="$store.bayiData.goToRekomendasi()"
                            onclick="window.mobileGoToRekomendasi && window.mobileGoToRekomendasi()"
                            class="group bg-gradient-to-r from-yellow-400 to-orange-400 hover:from-yellow-500 hover:to-orange-500 text-white font-bold py-4 px-8 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <span class="flex items-center gap-2">
                                Mulai Sekarang
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </span>
                        </button>
                    @else
                        <a href="{{ route('login') }}"
                            class="group bg-gradient-to-r from-yellow-400 to-orange-400 hover:from-yellow-500 hover:to-orange-500 text-white font-bold py-4 px-8 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 inline-flex items-center gap-2">
                            Mulai Sekarang
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Illustration -->
            <div class="relative flex justify-center items-center">
                <!-- Lingkaran dreamy -->
                <div class="relative">
                    <img src="{{ asset('bahan/log.png') }}"
                        class="relative w-80 lg:w-[450px] drop-shadow-2xl transform hover:scale-105 transition-transform duration-500"
                        alt="MPASI Illustration" />
                    <!-- Floating Elements -->
                    <div class="absolute -top-4 -right-4 bg-white p-3 rounded-2xl shadow-lg animate-bounce">
                        <span class="text-2xl">🥕</span>
                    </div>
                    <div
                        class="absolute -bottom-4 -left-4 bg-white p-3 rounded-2xl shadow-lg animate-bounce delay-300">
                        <span class="text-2xl">🍎</span>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- Wave transisi gradient -->
    <section class="bg-gradient-to-t from-yellow-50 to-yellow-100 relative overflow-hidden">
        <svg class="relative block w-full h-32" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"
            preserveAspectRatio="none">
            <defs>
                <linearGradient id="waveGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#fef9c3" stop-opacity="1" /> <!-- yellow-50 -->
                    <stop offset="100%" stop-color="#fde68a" stop-opacity="1" /> <!-- yellow-200 -->
                </linearGradient>
            </defs>
            <path fill="url(#waveGradient)"
                d="M0,128L48,133.3C96,139,192,149,288,154.7C384,160,480,160,576,138.7C672,117,768,75,864,64C960,53,1056,75,1152,101.3C1248,128,1344,160,1392,176L1440,192L1440,320L0,320Z">
            </path>
        </svg>
    </section>

    <!-- Fitur Section -->
    <section class="bg-gradient-to-t from-yellow-50 via-yellow-100 to-yellow-50 px-8 py-12">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-3xl font-black text-gray-900 mb-4">
                Kenapa Memilih <span class="text-orange-500">GEMAS</span>?
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Platform all-in-one untuk perjalanan MPASI si kecil
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-center">

            <!-- Feature 1 -->
            <div class="text-center group">

                @auth
                    <button @click="$store.bayiData.goToRekomendasi()"
                        onclick="window.mobileGoToRekomendasi && window.mobileGoToRekomendasi()" class="w-full">

                        <!-- Large Floating Icon -->
                        <div class="relative inline-block mb-8">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-yellow-400 to-yellow-400 rounded-full blur-2xl opacity-30 group-hover:opacity-50 transition-opacity">
                            </div>
                            <div
                                class="relative bg-gradient-to-br from-yellow-50 to-yellow-50 rounded-full p-12 group-hover:scale-110 transition-transform duration-500">
                                <img src="{{ asset('bahan/kalkulator.png') }}" alt="Kalkulator"
                                    class="w-24 h-24 mx-auto" />
                            </div>

                            <!-- Badge -->
                            <div
                                class="absolute -top-2 -right-2 bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                GRATIS
                            </div>
                        </div>

                        <!-- Content -->
                        <h3 class="text-2xl font-black text-gray-900 mb-4 group-hover:text-yellow-600 transition-colors">
                            Rekomendasi MPASI
                        </h3>
                        <p class="text-gray-600 text-lg leading-relaxed mb-6">
                            Hitung kebutuhan kalori bayi dan dapatkan rekomendasi menu MPASI yang tepat
                        </p>
                    </button>
                @else
                    <a href="{{ route('login') }}" class="block">
                        <!-- Large Floating Icon -->
                        <div class="relative inline-block mb-8">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-yellow-400 to-yellow-400 rounded-full blur-2xl opacity-30 group-hover:opacity-50 transition-opacity">
                            </div>
                            <div
                                class="relative bg-gradient-to-br from-yellow-50 to-yellow-50 rounded-full p-12 group-hover:scale-110 transition-transform duration-500">
                                <img src="{{ asset('bahan/kalkulator.png') }}" alt="Kalkulator"
                                    class="w-24 h-24 mx-auto" />
                            </div>

                            <!-- Badge -->
                            <div
                                class="absolute -top-2 -right-2 bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                GRATIS
                            </div>
                        </div>

                        <!-- Content -->
                        <h3 class="text-2xl font-black text-gray-900 mb-4 group-hover:text-yellow-600 transition-colors">
                            Rekomendasi MPASI
                        </h3>
                        <p class="text-gray-600 text-lg leading-relaxed mb-6">
                            Hitung kebutuhan kalori bayi dan dapatkan rekomendasi menu MPASI yang tepat
                        </p>
                    </a>
                @endauth
            </div>

            <!-- Feature 2 -->
            <div class="text-center group">
                <a href="{{ route('artikel') }}" class="block">
                    <!-- Large Floating Icon -->
                    <div class="relative inline-block mb-8">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-yellow-400 to-yellow-400 rounded-full blur-2xl opacity-30 group-hover:opacity-50 transition-opacity">
                        </div>
                        <div
                            class="relative bg-gradient-to-br from-yellow-50 to-yellow-50 rounded-full p-12 group-hover:scale-110 transition-transform duration-500">
                            <img src="{{ asset('bahan/arikel.png') }}" alt="Artikel" class="w-24 h-24 mx-auto" />
                        </div>

                        <!-- Badge -->
                        <div
                            class="absolute -top-2 -right-2 bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                            100+ Artikel
                        </div>
                    </div>

                    <!-- Content -->
                    <h3 class="text-2xl font-black text-gray-900 mb-4 group-hover:text-yellow-600 transition-colors">
                        Artikel Edukatif
                    </h3>
                    <p class="text-gray-600 text-lg leading-relaxed mb-6">
                        Baca artikel tentang tips, panduan, dan informasi seputar MPASI dari ahli
                    </p>
                </a>
            </div>
        </div>
        </div>
    </section>

    <!-- gallery mpasi section -->
    <section class="w-full bg-yellow-50 py-10">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Galeri Menu MPASI</h2>
                <p class="text-xl text-gray-600">Inspirasi menu lezat dan bergizi untuk si kecil</p>
            </div>

            <div class="grid gap-4">
                <!-- Gambar besar -->
                <div class="overflow-hidden rounded-lg">
                    <img id="mainImage" class="h-auto w-full max-w-full rounded-lg object-cover object-center"
                        src="{{ asset('bahan/mpasi3.jpg') }}" alt="Gambar utama" />
                </div>

                <!-- Thumbnail -->
                <div class="grid grid-cols-5 gap-4">
                    <img onclick="changeImage(this)" src="{{ asset('bahan/mpasi1.jpg') }}"
                        class="h-20 w-full cursor-pointer rounded-lg object-cover hover:opacity-75" alt="Thumb 1" />
                    <img onclick="changeImage(this)" src="{{ asset('bahan/mpasi2.jpg') }}"
                        class="h-20 w-full cursor-pointer rounded-lg object-cover hover:opacity-75" alt="Thumb 2" />
                    <img onclick="changeImage(this)" src="{{ asset('bahan/mpasi3.jpg') }}"
                        class="h-20 w-full cursor-pointer rounded-lg object-cover hover:opacity-75" alt="Thumb 3" />
                    <img onclick="changeImage(this)" src="{{ asset('bahan/mpasi4.jpg') }}"
                        class="h-20 w-full cursor-pointer rounded-lg object-cover hover:opacity-75" alt="Thumb 4" />
                    <img onclick="changeImage(this)" src="{{ asset('bahan/mpasi5.jpg') }}"
                        class="h-20 w-full cursor-pointer rounded-lg object-cover hover:opacity-75" alt="Thumb 5" />
                </div>
            </div>
        </div>
    </section>
    <!-- end gallery mpasi section -->

    <!-- Artikel section -->
    <section class="w-full bg-yellow-50 py-10">
        <div class="container mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Artikel Terkini</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Temukan berbagai artikel menarik dan informatif seputar Makanan Pendamping ASI untuk mendukung
                    pertumbuhan si kecil
                </p>
            </div>

            <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-8">
                @if (isset($artikels[0]))
                    <!-- Featured Article -->
                    <a href="{{ route('artikel.show', $artikels[0]->slug) }}"
                        class="group relative h-[500px] overflow-hidden rounded-3xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-500">

                        @if ($artikels[0]->gambar_url)
                            <img src="{{ $artikels[0]->gambar_url }}" alt="{{ $artikels[0]->judul }}"
                                onerror="this.onerror=null;this.remove();"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent">
                            </div>
                        @else
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-orange-400 via-amber-500 to-yellow-400">
                                <div class="absolute inset-0 opacity-20"
                                    style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.4&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
                                </div>
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent">
                            </div>
                        @endif

                        <div class="absolute inset-x-0 bottom-0 p-8">
                            <span
                                class="inline-block bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full mb-4">
                                Terbaru
                            </span>
                            <h3
                                class="text-3xl font-bold text-white mb-3 drop-shadow-lg group-hover:text-yellow-300 transition-colors">
                                {{ $artikels[0]->judul }}
                            </h3>
                            <div class="flex items-center gap-3 text-white/90">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd"
                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="font-semibold">Baca Selengkapnya</span>
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </a>
                @endif

                <!-- Side Articles -->
                <div class="space-y-8">
                    @foreach ([$artikels[1] ?? null, $artikels[2] ?? null] as $artikel)
                        @if ($artikel)
                            <a href="{{ route('artikel.show', $artikel->slug) }}"
                                class="group relative block h-56 overflow-hidden rounded-3xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">

                                @if ($artikel->gambar_url)
                                    <img src="{{ $artikel->gambar_url }}" alt="{{ $artikel->judul }}"
                                        onerror="this.onerror=null;this.remove();"
                                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent">
                                    </div>
                                @else
                                    <div
                                        class="absolute inset-0 bg-gradient-to-br from-pink-400 via-rose-500 to-orange-400">
                                        <div class="absolute inset-0 opacity-20"
                                            style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.4&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
                                        </div>
                                    </div>
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent">
                                    </div>
                                @endif

                                <div class="absolute inset-x-0 bottom-0 p-6">
                                    <h3
                                        class="text-xl font-bold text-white mb-2 drop-shadow-lg group-hover:text-yellow-300 transition-colors">
                                        {{ $artikel->judul }}
                                    </h3>
                                    <div class="flex items-center gap-2 text-white/90 text-sm">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd"
                                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span>Baca Artikel</span>
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- pertanyaan umum section -->
    <section class="bg-gradient-to-b from-amber-50 to-yellow-50 py-20 px-6">
        <div class="container mx-auto max-w-3xl">
            <div class="text-center mb-12">
                <span
                    class="inline-block bg-yellow-200 text-yellow-900 text-sm font-semibold px-4 py-2 rounded-full mb-4">
                    ❓ FAQ
                </span>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Pertanyaan yang Sering Diajukan</h2>
                <p class="text-xl text-gray-600">Temukan jawaban untuk pertanyaan umum tentang MPASI</p>
            </div>

            <div class="space-y-4" x-data="{ open1: false, open2: false }">
                <!-- FAQ 1 -->
                <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                    <button @click="open1 = !open1"
                        class="w-full p-6 text-left flex justify-between items-center hover:bg-gray-50 transition-colors">
                        <span class="text-lg font-bold text-gray-900 pr-4">Apa itu MPASI?</span>
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                            <svg :class="open1 ? 'rotate-180' : ''"
                                class="w-5 h-5 text-yellow-600 transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    <div x-show="open1" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0" class="px-6 pb-6">
                        <div class="pt-4 border-t border-gray-100">
                            <p class="text-gray-700 leading-relaxed">
                                MPASI merupakan singkatan dari Makanan Pendamping Air Susu Ibu. Ini adalah makanan padat
                                atau makanan cair yang diberikan pada periode penyapihan disaat ASI saja sudah tidak
                                dapat mencukupi kebutuhan nutrisi untuk tumbuh kembang, yang diberikan mulai bayi
                                berumur 6 bulan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-shadow overflow-hidden">
                    <button @click="open2 = !open2"
                        class="w-full p-6 text-left flex justify-between items-center hover:bg-gray-50 transition-colors">
                        <span class="text-lg font-bold text-gray-900 pr-4">Bagaimana sistem rekomendasi di GEMAS
                            bekerja?</span>
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                            <svg :class="open2 ? 'rotate-180' : ''"
                                class="w-5 h-5 text-yellow-600 transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    <div x-show="open2" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0" class="px-6 pb-6">
                        <div class="pt-4 border-t border-gray-100">
                            <p class="text-gray-700 leading-relaxed">
                                Sistem kami menghitung kebutuhan kalori harian bayi berdasarkan berat badan, umur bayi,
                                dan kalori jenis susu yang diberikan. Kemudian kami menghitung MPASI yang cocok untuk
                                bayi tersebut dengan akurasi tinggi dan rekomendasi yang personal.
                            </p>
                        </div>
    </section>

    <!-- Modal Data Bayi -->
    @auth
        <div x-data="bayiModalComponent()" x-cloak x-init="init()" x-show="open"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-start justify-center z-50 overflow-y-auto p-2 sm:p-4"
            x-transition.opacity.duration.300ms>

            <!-- Modal Box -->
            <div class="bg-white rounded-xl w-full max-w-sm sm:max-w-md lg:max-w-lg max-h-[95vh] mx-2 shadow-2xl relative flex flex-col"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                @click.away="closeModal()">

                <!-- Header Fixed -->
                <div
                    class="bg-white border-b border-gray-200 px-4 sm:px-6 py-3 sm:py-4 flex-shrink-0 rounded-t-xl relative">
                    <!-- Close Button -->
                    <button @click="closeModal()"
                        class="absolute top-3 sm:top-4 right-3 sm:right-4 text-gray-400 hover:text-gray-600 text-lg sm:text-xl font-bold z-10 w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center hover:bg-gray-100 rounded-full transition-colors">
                        ✕
                    </button>

                    <!-- Header Title -->
                    <div class="text-center pr-8 sm:pr-10">
                        <h2 class="text-lg sm:text-xl font-bold mb-1 sm:mb-2">👶 Haii {{ Auth::user()->nama }}✨</h2>
                        <p class="text-gray-600 text-xs sm:text-sm leading-tight sm:leading-normal">
                            Isi data si kecil biar dapat rekomendasi MPASI sehat dan bergizi
                        </p>
                    </div>
                </div>

                <!-- Scrollable Content -->
                <div class="overflow-y-auto flex-1 px-4 sm:px-6 py-4 sm:py-6">
                    <!-- Bayi Selection -->
                    <!-- Form -->
                    <form @submit.prevent="onSubmit()" class="space-y-3 sm:space-y-4">
                        <!-- Hidden ID biar selalu terkirim -->
                        <input type="hidden" x-model="selectedBayi.id">

                        <div class="mb-3 sm:mb-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                            <label class="block text-sm font-medium">Pilih Bayi</label>
                            <button @click="addNewBayi()" type="button"
                                class="text-yellow-600 hover:underline text-xs sm:text-sm font-medium bg-yellow-50 px-2 py-1 rounded-md hover:bg-yellow-100 transition-colors self-start sm:self-auto">
                                + Tambah Bayi Baru
                            </button>
                        </div>

                        <!-- Error Message -->
                        @if ($errors->has('usia'))
                            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 10000)" {{-- Auto-hide setelah 10 detik --}}
                                x-transition
                                class="relative bg-red-100 border border-red-400 text-red-700 px-3 sm:px-4 py-2 sm:py-3 rounded mb-3 sm:mb-4 text-xs sm:text-sm">
                                <span>{{ $errors->first('usia') }}</span>

                                <!-- Tombol close -->
                                <button type="button" @click="show = false"
                                    class="absolute top-1 right-1 text-red-600 hover:text-red-800">
                                    &times;
                                </button>
                            </div>
                        @endif
                        <!-- Bayi Dropdown -->
                        <select x-model="selectedBayi.id" @change="onBayiChange()"
                            class="border border-gray-300 p-2 sm:p-3 w-full rounded-md mb-3 sm:mb-4 focus:ring-2 focus:ring-yellow-400 focus:border-transparent text-sm">
                            <option value="">-- Pilih Bayi --</option>
                            <template x-for="bayi in bayis" :key="bayi.id">
                                <option :value="bayi.id" x-text="bayi.nama_bayi + ' (' + bayi.jenis_kelamin + ')'">
                                </option>
                            </template>
                        </select>

                        <!-- Nama Bayi -->
                        <div>
                            <label class="block text-sm font-medium mb-1">Nama Bayi <span
                                    class="text-red-500">*</span></label>
                            <input type="text" x-model="selectedBayi.nama_bayi" required
                                class="w-full border p-2 sm:p-3 rounded-md focus:ring-2 focus:outline-none focus:ring-yellow-400 focus:border-transparent text-sm"
                                placeholder="Masukkan nama bayi">
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label class="block text-sm font-medium mb-1">Tanggal Lahir <span
                                    class="text-red-500">*</span></label>
                            <input type="date" x-model="selectedBayi.tanggal_lahir" @change="updateUmur()" required
                                class="w-full border p-2 sm:p-3 rounded-md focus:ring-2 focus:outline-none focus:ring-yellow-400 focus:border-transparent text-sm"
                                :max="new Date().toISOString().split('T')[0]">
                        </div>

                        <!-- Umur (Read-only) -->
                        <div>
                            <label class="block text-sm font-medium mb-1">Umur</label>
                            <input type="text" x-model="selectedBayi.umur" readonly
                                class="w-full border p-2 sm:p-3 rounded-md focus:ring-2 focus:outline-none focus:ring-yellow-400 focus:border-transparent text-sm"
                                placeholder="Akan terisi otomatis">
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label class="block text-sm font-medium mb-1">Jenis Kelamin <span
                                    class="text-red-500">*</span></label>
                            <select x-model="selectedBayi.jenis_kelamin" required
                                class="w-full border p-2 sm:p-3 rounded-md focus:ring-2 focus:outline-none focus:ring-yellow-400 focus:border-transparent text-sm">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>

                        <!-- Row untuk Berat dan Tinggi di desktop -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <!-- Berat -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Berat Badan (kg) <span
                                        class="text-red-500">*</span></label>
                                <input type="number" step="0.1" min="2" max="15"
                                    x-model="selectedBayi.berat" required
                                    class="w-full border p-2 sm:p-3 rounded-md focus:ring-2 focus:outline-none focus:ring-yellow-400 focus:border-transparent text-sm"
                                    placeholder="contoh: 8.5">
                            </div>

                            <!-- Tinggi -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Tinggi Badan (cm) <span
                                        class="text-red-500">*</span></label>
                                <input type="number" step="0.1" min="40" max="100"
                                    x-model="selectedBayi.tinggi" required
                                    class="w-full border p-2 sm:p-3 rounded-md focus:ring-2 focus:outline-none focus:ring-yellow-400 focus:border-transparent text-sm"
                                    placeholder="contoh: 78.5">
                            </div>
                        </div>

                        <!-- Jenis Susu -->
                        <div>
                            <label class="block text-sm font-medium mb-1">Jenis Susu <span
                                    class="text-red-500">*</span></label>
                            <select x-model="selectedBayi.jenis_susu"
                                class="w-full border p-2 sm:p-3 rounded-md focus:ring-2 focus:outline-none focus:ring-yellow-400 focus:border-transparent text-sm">
                                <option value="">-- Pilih Jenis Susu --</option>
                                <option value="ASI">ASI</option>
                                <option value="Sufor">Susu formula</option>
                                <option value="Mix">Campuran (ASI dan Susu formula)</option>
                            </select>
                        </div>

                        <!-- Volume ASI -->
                        <div x-show="selectedBayi.jenis_susu === 'ASI' || selectedBayi.jenis_susu === 'Mix'">
                            <label class="block text-sm font-medium mb-1">Volume ASI per hari (ml) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" step="1" min="200" max="1000"
                                x-model="selectedBayi.volume_asi"
                                class="w-full border p-2 sm:p-3 rounded-md focus:ring-2 focus:outline-none focus:ring-yellow-400 focus:border-transparent text-sm"
                                placeholder="contoh: 600">
                        </div>

                        <!-- kalori per porsi -->
                        <div x-show="selectedBayi.jenis_susu === 'Sufor' || selectedBayi.jenis_susu === 'Mix'">
                            <label class="block text-sm font-medium mb-1">Kalori per Porsi Sufor (kcal) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" step="1" min="20" max="120"
                                x-model="selectedBayi.kalori_per_porsi"
                                class="w-full border p-2 sm:p-3 rounded-md focus:ring-2 focus:outline-none focus:ring-yellow-400 focus:border-transparent text-sm"
                                placeholder="contoh: 67">
                        </div>
                        <!-- End kalori per porsi -->
                        <!--jumlah porsi per hari-->
                        <div x-show="selectedBayi.jenis_susu === 'Sufor' || selectedBayi.jenis_susu === 'Mix'">
                            <label
                                class="block font-medium mb-1 focus:ring-2 focus:ring-yellow-400 focus:border-transparent text-sm">Jumlah
                                Porsi Sufor per hari <span class="text-red-500">*</span></label>
                            <input type="number" step="1" min="1" max="8"
                                x-model="selectedBayi.jumlah_porsi_per_hari"
                                class="w-full border p-2 sm:p-3 rounded-md focus:ring-2 focus:outline-none focus:ring-yellow-400 focus:border-transparent text-sm"
                                placeholder="contoh: 5">
                        </div>
                        <!--end jumlah porsi per hari-->

                        <!-- Buttons Container -->
                        <div class="pt-3 sm:pt-4 space-y-2 sm:space-y-3">
                            <!-- Submit Button -->
                            <button type="submit" :disabled="isSubmitting"
                                class="w-full px-4 py-2.5 sm:py-3 rounded-md font-semibold transition-colors duration-200 text-sm"
                                :class="isSubmitting ? 'bg-gray-400 cursor-not-allowed text-white' :
                                    'bg-yellow-400 hover:bg-yellow-500 text-white'">
                                <span x-show="!isSubmitting">Simpan & Lanjutkan</span>
                                <span x-show="isSubmitting">Menyimpan...</span>
                            </button>

                            <!-- Save Only Button -->
                            <button type="button" @click="$store.bayiData.saveOnly()"
                                class="w-full px-4 py-2.5 sm:py-3 rounded-md font-semibold bg-blue-400 hover:bg-blue-500 text-white text-sm transition-colors duration-200">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endauth
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
                        <a href="{{ route('landingpg') }}"
                            class="text-base text-gray-700 hover:text-orange-600 transition-colors flex items-center group">
                            <svg class="w-4 h-4 mr-2 text-orange-500 opacity-0 group-hover:opacity-100 transform -translate-x-2 group-hover:translate-x-0 transition-all"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                            <span class="group-hover:translate-x-1 transition-transform">Beranda</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('rekomendasi') }}"
                            class="text-base text-gray-700 hover:text-orange-600 transition-colors flex items-center group">
                            <svg class="w-4 h-4 mr-2 text-orange-500 opacity-0 group-hover:opacity-100 transform -translate-x-2 group-hover:translate-x-0 transition-all"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                            <span class="group-hover:translate-x-1 transition-transform">Rekomendasi MPASI</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('perkembangan.index') }}"
                            class="text-base text-gray-700 hover:text-orange-600 transition-colors flex items-center group">
                            <svg class="w-4 h-4 mr-2 text-orange-500 opacity-0 group-hover:opacity-100 transform -translate-x-2 group-hover:translate-x-0 transition-all"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                            <span class="group-hover:translate-x-1 transition-transform">Perkembangan Bayi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('artikel') }}"
                            class="text-base text-gray-700 hover:text-orange-600 transition-colors flex items-center group">
                            <svg class="w-4 h-4 mr-2 text-orange-500 opacity-0 group-hover:opacity-100 transform -translate-x-2 group-hover:translate-x-0 transition-all"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
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
                © 2025 GEMAS All rights reserved.
            </p>
        </div>
    </footer>
    <!-- end footer -->
    <!-- Notifikasi Global -->
    <div x-data="{ show: false, message: '', type: 'success' }" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2" x-cloak class="fixed top-5 right-5 z-50"
        @notif.window="
        message = $event.detail.message;
        type = $event.detail.type;
        show = true;
        setTimeout(() => show = false, 3000);
    ">
        <div :class="{
            'bg-green-100 border-green-400 text-green-700': type === 'success',
            'bg-red-100 border-red-400 text-red-700': type === 'error'
        }"
            class="border px-4 py-3 rounded-lg shadow-lg flex items-center space-x-2" role="alert">
            <svg x-show="type === 'success'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <svg x-show="type === 'error'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span x-text="message" class="font-medium"></span>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('bayiData', {
                bayis: @json($bayis ?? []),
                selectedBayi: {},

                modalOpen: false,
                isSubmitting: false,
                userRole: "{{ Auth::user()->role ?? '' }}",


                // 🔹 INIT
                init() {
                    const hasSession = @json(session('selectedBayiId') ?? null);
                    const bayisEmpty = this.bayis.length === 0;
                    const userRole = "{{ Auth::user()->role ?? 'user' }}"; // ambil role dari server

                    // ❌ kalau admin, jangan buka modal
                    if (userRole === 'admin') {
                        this.modalOpen = false;
                        return;
                    }

                    // ✅ kalau bukan admin dan bayi kosong, baru buka modal
                    this.modalOpen = bayisEmpty;

                    if (hasSession) {
                        const sessionBayi = this.bayis.find(b => b.id == hasSession);
                        if (sessionBayi) {
                            this.selectedBayi = {
                                ...sessionBayi
                            };
                            return;
                        }
                    }

                    if (this.bayis.length > 0) {
                        this.selectedBayi = {
                            ...this.bayis[0]
                        };
                    } else {
                        this.resetSelectedBayi();
                    }
                },

                // 🔹 RESET FORM
                resetSelectedBayi() {
                    this.selectedBayi = {
                        id: null,
                        nama_bayi: '',
                        tanggal_lahir: '',
                        umur: '',
                        jenis_kelamin: '',
                        berat: '',
                        tinggi: '',
                        jenis_susu: '',
                        volume_asi: '',
                        kalori_per_porsi: '',
                        jumlah_porsi_per_hari: ''
                    };
                },

                // 🔹 SIMPAN TANPA KELUAR
                async saveOnly() {
                    if (!this.validateFormSaveOnly()) return; // kirim true biar validasi ringan
                    this.isSubmitting = true;
                    try {
                        let formData = new FormData();

                        if (this.selectedBayi.id) {
                            formData.append("id", this.selectedBayi.id);
                        }

                        const bayiData = {
                            ...this.selectedBayi
                        };

                        // Hapus field yang gak relevan
                        if (bayiData.jenis_susu === 'ASI') {
                            delete bayiData.kalori_per_porsi;
                            delete bayiData.jumlah_porsi_per_hari;
                        } else if (bayiData.jenis_susu === 'Sufor') {
                            delete bayiData.volume_asi;
                        }

                        Object.keys(bayiData).forEach(key => {
                            const val = bayiData[key];
                            if (val !== null && val !== '') {
                                formData.append(key, val);
                            }
                        });

                        const response = await fetch("{{ route('bayi.updateOrCreate') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name=\"csrf-token\"]').content
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (!response.ok || !data.success) {
                            throw new Error(data.message || "Gagal menyimpan data bayi.");
                        }

                        this.bayis = data.bayis;
                        this.selectedBayi = data.selectedBayi;

                        window.dispatchEvent(new CustomEvent('notif', {
                            detail: {
                                message: "Data bayi berhasil disimpan!",
                                type: 'success'
                            }
                        }));

                    } catch (error) {
                        console.error("SaveOnly error:", error);
                        window.dispatchEvent(new CustomEvent('notif', {
                            detail: {
                                message: error.message || "Gagal menyimpan data bayi.",
                                type: 'error'
                            }
                        }));
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                // 🔹 HITUNG UMUR
                updateUmur() {
                    if (this.selectedBayi.tanggal_lahir) {
                        const birth = new Date(this.selectedBayi.tanggal_lahir);
                        const now = new Date();
                        let months = (now.getFullYear() - birth.getFullYear()) * 12 + (now.getMonth() -
                            birth.getMonth());
                        if (now.getDate() < birth.getDate()) months--;
                        this.selectedBayi.umur = Math.max(0, months) + ' bulan';
                    } else {
                        this.selectedBayi.umur = '';
                    }
                },

                // 🔹 GANTI BAYI TERPILIH
                onBayiChange() {
                    const bayi = this.bayis.find(b => b.id == this.selectedBayi.id);
                    if (bayi) {
                        this.selectedBayi = {
                            ...bayi
                        };
                        this.updateUmur();
                    } else {
                        this.resetSelectedBayi();
                    }
                },

                // 🔹 TAMBAH BARU
                addNewBayi() {
                    this.resetSelectedBayi();
                },

                // 🔹 SUBMIT FORM
                async submitForm() {
                    if (!this.validateForm()) return;
                    this.isSubmitting = true;

                    try {
                        const bayiData = {
                            ...this.selectedBayi
                        };

                        if (bayiData.jenis_susu === 'ASI') {
                            delete bayiData.kalori_per_porsi;
                            delete bayiData.jumlah_porsi_per_hari;
                        } else if (bayiData.jenis_susu === 'Sufor') {
                            delete bayiData.volume_asi;
                        }

                        // konversi angka
                        ['berat', 'tinggi', 'volume_asi', 'kalori_per_porsi', 'jumlah_porsi_per_hari']
                        .forEach(
                            k => {
                                if (bayiData[k]) bayiData[k] = parseFloat(bayiData[k]);
                            });

                        const response = await axios.post("{{ route('bayi.updateOrCreate') }}", {
                            _token: "{{ csrf_token() }}",
                            ...bayiData
                        });

                        if (response.data && response.data.success !== false) {
                            this.bayis = response.data.bayis || this.bayis;
                            this.selectedBayi = response.data.selectedBayi || this.selectedBayi;

                            if (response.data.redirect) {
                                window.location.href = response.data.redirect;
                            } else {
                                this.modalOpen = false;
                                this.showSuccessMessage();
                            }
                        } else {
                            throw new Error(response.data.message || 'Unknown error');
                        }
                    } catch (error) {
                        console.error('Error saving bayi data:', error);
                        this.showErrorMessage(error);
                    } finally {
                        this.isSubmitting = false;
                    }
                },
                // Utility: sanitasi pesan biar gak ada HTML
                sanitizeText(text) {
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerText || div.textContent || '';
                },
                // 🔹 VALIDASI untuk simpan saja
                validateFormSaveOnly() {
                    const b = this.selectedBayi;

                    // --- Wajib diisi ---
                    const required = ['nama_bayi', 'tanggal_lahir', 'jenis_kelamin'];

                    for (let f of required) {
                        if (!b[f]) {
                            window.dispatchEvent(new CustomEvent('notif', {
                                detail: {
                                    message: `Kolom ${f.replace('_', ' ')} wajib diisi!`,
                                    type: 'error'
                                }
                            }));
                            return false;
                        }
                    }

                    // --- Validasi tanggal lahir ---
                    const today = new Date();
                    const birthDate = new Date(b.tanggal_lahir);
                    if (birthDate > today) {
                        window.dispatchEvent(new CustomEvent('notif', {
                            detail: {
                                message: "Tanggal lahir tidak boleh di masa depan!",
                                type: 'error'
                            }
                        }));
                        return false;
                    }

                    // --- Validasi nama ---
                    if (b.nama_bayi.length > 100 || !/^[a-zA-Z\s'-]+$/.test(b.nama_bayi)) {
                        window.dispatchEvent(new CustomEvent('notif', {
                            detail: {
                                message: "Nama bayi tidak boleh lebih dari 100 karakter dan hanya boleh mengandung huruf, spasi, tanda petik, dan tanda hubung!",
                                type: 'error'
                            }
                        }));
                        return false;
                    }



                    // --- Validasi numerik opsional ---
                    const numericFields = {
                        berat: {
                            min: 2,
                            max: 15,
                            unit: 'kg'
                        },
                        tinggi: {
                            min: 40,
                            max: 100,
                            unit: 'cm'
                        },
                        volume_asi: {
                            min: 200,
                            max: 1000,
                            unit: 'ml'
                        },
                        kalori_per_porsi: {
                            min: 20,
                            max: 120,
                            unit: 'kkal'
                        },
                        jumlah_porsi_per_hari: {
                            min: 1,
                            max: 8,
                            unit: 'kali'
                        },
                    };

                    for (const [key, rule] of Object.entries(numericFields)) {
                        const val = parseFloat(b[key]);
                        if (b[key] && (isNaN(val) || val < rule.min || val > rule.max)) {
                            window.dispatchEvent(new CustomEvent('notif', {
                                detail: {
                                    message: `${key.replace('_', ' ')} harus di antara ${rule.min}–${rule.max} ${rule.unit}.`,
                                    type: 'error'
                                }
                            }));
                            return false;
                        }
                    }



                    // --- Berdasarkan jenis susu ---
                    if (b.jenis_susu === 'ASI' && b.volume_asi && (b.volume_asi < 200 || b.volume_asi >
                            1000)) {
                        window.dispatchEvent(new CustomEvent('notif', {
                            detail: {
                                message: 'Volume ASI harus di antara 200–1000 ml.',
                                type: 'error'
                            }
                        }));
                        return false;
                    }

                    if (b.jenis_susu === 'Sufor') {
                        if ((b.kalori_per_porsi && (b.kalori_per_porsi < 20 || b.kalori_per_porsi > 120)) ||
                            (b.jumlah_porsi_per_hari && (b.jumlah_porsi_per_hari < 1 || b
                                .jumlah_porsi_per_hari > 8))) {
                            window.dispatchEvent(new CustomEvent('notif', {
                                detail: {
                                    message: 'Kalori per porsi 20–120 kkal dan porsi per hari 1–8 kali.',
                                    type: 'error'
                                }
                            }));
                            return false;
                        }
                    }

                    return true;
                },


                // 🔹 VALIDASI untuk simpan & lanjutkan
                validateForm() {
                    const b = this.selectedBayi;

                    // --- Wajib diisi ---
                    const required = ['nama_bayi', 'tanggal_lahir', 'jenis_kelamin', 'jenis_susu'];
                    for (let f of required) {
                        if (!b[f]) {
                            window.dispatchEvent(new CustomEvent('notif', {
                                detail: {
                                    message: `Kolom ${f.replace('_', ' ')} wajib diisi!`,
                                    type: 'error'
                                }
                            }));
                            return false;
                        }
                    }
                    //--- End Wajib diisi ---
                    //--- Validasi tanggal lahir tidak boleh di masa depan --->
                    const today = new Date();
                    const birthDate = new Date(b.tanggal_lahir);
                    if (birthDate > today) {
                        window.dispatchEvent(new CustomEvent('notif', {
                            detail: {
                                message: "Tanggal lahir tidak boleh di masa depan!",
                                type: 'error'
                            }
                        }));
                        return false;
                    }
                    //--- End Validasi tanggal lahir tidak boleh di masa depan --->
                    //--validasi string dan karakter > 100 untuk nama bayi---
                    if (b.nama_bayi.length > 100 || !/^[a-zA-Z\s'-]+$/.test(b.nama_bayi)) {
                        window.dispatchEvent(new CustomEvent('notif', {
                            detail: {
                                message: "Nama bayi tidak boleh lebih dari 100 karakter dan hanya boleh mengandung huruf, spasi, tanda petik, dan tanda hubung!",
                                type: 'error'
                            }
                        }));
                        return false;
                    }
                    //--- End validasi string untuk nama bayi---

                    //--- validasi jenis kelamin ---
                    const validGenders = ['Laki-laki', 'Perempuan'];
                    if (!validGenders.includes(b.jenis_kelamin)) {
                        window.dispatchEvent(new CustomEvent('notif', {
                            detail: {
                                message: "Jenis kelamin harus Laki-laki atau Perempuan!",
                                type: 'error'
                            }
                        }));
                        return false;
                    }
                    //--- End validasi jenis kelamin ---

                    // --- Validasi numerik opsional ---
                    const numericFields = {
                        berat: {
                            min: 2,
                            max: 15,
                            unit: 'kg'
                        },
                        tinggi: {
                            min: 40,
                            max: 100,
                            unit: 'cm'
                        },
                        volume_asi: {
                            min: 200,
                            max: 1000,
                            unit: 'ml'
                        },
                        kalori_per_porsi: {
                            min: 20,
                            max: 120,
                            unit: 'kkal'
                        },
                        jumlah_porsi_per_hari: {
                            min: 1,
                            max: 8,
                            unit: 'kali'
                        },
                    };

                    for (const [key, rule] of Object.entries(numericFields)) {
                        const val = parseFloat(b[key]);
                        if (b[key] && (isNaN(val) || val < rule.min || val > rule.max)) {
                            window.dispatchEvent(new CustomEvent('notif', {
                                detail: {
                                    message: `${key.replace('_', ' ')} harus di antara ${rule.min}–${rule.max} ${rule.unit}.`,
                                    type: 'error'
                                }
                            }));
                            return false;
                        }
                    }

                    // --- Berdasarkan jenis susu ---
                    if (b.jenis_susu === 'ASI') {
                        if (!b.volume_asi) {
                            window.dispatchEvent(new CustomEvent('notif', {
                                detail: {
                                    message: 'Volume ASI harus diisi untuk bayi ASI.',
                                    type: 'error'
                                }
                            }));
                            return false;
                        }
                    } else if (b.jenis_susu === 'Sufor') {
                        if (!b.kalori_per_porsi || !b.jumlah_porsi_per_hari) {
                            window.dispatchEvent(new CustomEvent('notif', {
                                detail: {
                                    message: 'Kalori per porsi dan jumlah porsi per hari wajib diisi untuk bayi Sufor.',
                                    type: 'error'
                                }
                            }));
                            return false;
                        }
                    } else if (b.jenis_susu === 'Mix') {
                        if (!b.volume_asi || !b.kalori_per_porsi || !b.jumlah_porsi_per_hari) {
                            window.dispatchEvent(new CustomEvent('notif', {
                                detail: {
                                    message: 'Semua field ASI dan Sufor wajib diisi untuk bayi dengan jenis susu campuran.',
                                    type: 'error'
                                }
                            }));
                            return false;
                        }
                    }
                    return true;
                },
                // 🔹 NOTIFIKASI
                showSuccessMessage() {
                    Alpine.store('notif').trigger('success', 'Data bayi berhasil disimpan! ✅');
                },
                showErrorMessage(error) {
                    let msg = 'Gagal menyimpan data bayi. ';
                    if (error.response?.data?.message) {
                        msg += error.response.data.message;
                    } else if (error.message) {
                        msg += error.message;
                    } else {
                        msg += 'Silakan coba lagi.';
                    }

                    const cleanMsg = this.sanitizeText(msg);
                    Alpine.store('notif').trigger('error', cleanMsg);
                },
                // 🔹 MODAL CONTROL
                closeModal() {
                    this.modalOpen = false;
                    this.isSubmitting = false;
                },
                openModal() {
                    this.modalOpen = true;
                },

                // 🔹 BUKA MODAL DARI HALAMAN UTAMA
                goToRekomendasi() {
                    // Cek apakah user admin
                    if (this.userRole === 'admin') {
                        window.dispatchEvent(new CustomEvent('notif', {
                            detail: {
                                message: 'Hanya Pengguna biasa yang bisa menggunakannya.',
                                type: 'error'
                            }
                        }));
                        return; // Stop, jangan buka modal
                    }

                    // Kalau bukan admin, buka modal seperti biasa
                    if (!this.bayis || this.bayis.length === 0) {
                        this.openModal();
                        return;
                    }
                    this.openModal();
                }
            });
        });

        // Komponen Alpine Modal
        function bayiModalComponent() {
            return {
                get open() {
                    return Alpine.store('bayiData').modalOpen
                },
                get isSubmitting() {
                    return Alpine.store('bayiData').isSubmitting
                },
                get bayis() {
                    return Alpine.store('bayiData').bayis
                },
                get selectedBayi() {
                    return Alpine.store('bayiData').selectedBayi
                },

                init() {
                    Alpine.store('bayiData').init();
                },
                updateUmur() {
                    Alpine.store('bayiData').updateUmur();
                },
                onBayiChange() {
                    Alpine.store('bayiData').onBayiChange();
                },
                addNewBayi() {
                    Alpine.store('bayiData').addNewBayi();
                },
                onSubmit() {
                    Alpine.store('bayiData').submitForm();
                },
                closeModal() {
                    Alpine.store('bayiData').closeModal();
                }
            }
        }
    </script>

    <script>
        function changeImage(element) {
            document.getElementById("mainImage").src = element.src;
        }
        const mainImage = document.getElementById("mainImage");
        mainImage.addEventListener("mouseenter", () => mainImage.style.transform = "scale(1.1)");
        mainImage.addEventListener("mouseleave", () => mainImage.style.transform = "scale(1)");
    </script>
    <script>
        // Pastikan fungsi global tersedia
        document.addEventListener('DOMContentLoaded', function() {
            // Definisikan fungsi global untuk mobile
            window.mobileGoToRekomendasi = function() {
                console.log('mobileGoToRekomendasi called');

                // Tunggu sampai Alpine ready
                if (window.Alpine && window.Alpine.store) {
                    const store = window.Alpine.store('bayiData');
                    if (store && store.goToRekomendasi) {
                        console.log('Calling store.goToRekomendasi');
                        store.goToRekomendasi();
                    } else {
                        console.log('Store or method not found, redirecting...');
                        window.location.href = "{{ route('rekomendasi') }}";
                    }
                } else {
                    console.log('Alpine not ready, redirecting...');
                    window.location.href = "{{ route('rekomendasi') }}";
                }
            };

            // Alternative: Bind click event setelah DOM ready
            const mobileRekomendasiBtn = document.getElementById('mobile-rekomendasi-btn');
            if (mobileRekomendasiBtn) {
                mobileRekomendasiBtn.addEventListener('click', function() {
                    console.log('Mobile rekomendasi clicked via event listener');
                    window.mobileGoToRekomendasi();
                });
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (@json($errors->any())) {
                Alpine.store('bayiData').modalOpen = true;
            }
        });
    </script>

</body>

</html>
