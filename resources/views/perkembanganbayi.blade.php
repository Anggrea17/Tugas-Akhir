<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perkembangan Bayi</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
      <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        .card-shadow {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .chart-container {
            position: relative;
            transition: transform 0.3s ease;
        }

        .chart-container:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body class="text-brown-800 min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">

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

                    <div x-show="open" @click.away="open = false"
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
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-1 px-4 py-2 bg-indigo-500 text-white text-sm rounded-lg shadow hover:bg-indigo-600 transition whitespace-nowrap">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.232 5.232l3.536 3.536M9 11l6-6m2 2L9 17H7v-2l8-8z" />
                                </svg>
                                Edit Profile
                            </a>

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

    <!-- Main Content -->

    <header
        class="text-center mb-10 animate-fade-in bg-gradient-to-br from-yellow-100 via-yellow-50 to-yellow-95 rounded-3xl shadow-lg py-10 ">
        <div class="inline-block gradient-bg rounded-full p-4 mb-4">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                </path>
            </svg>
        </div>
        <h1 class="text-4xl font-bold text-yellow-900 mb-2">Grafik Perkembangan Bayi</h1>
        <p class="text-gray-600">Pantau pertumbuhan si kecil dengan mudah</p>
    </header>

    <!-- Main Card -->
    <div class="bg-white rounded-3xl card-shadow p-8 animate-fade-in">

        @if (count($bayis) === 0)
            <div class="text-center py-12">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <p class="text-xl text-gray-500">Belum ada data bayi</p>
                <p class="text-gray-400 mt-2">Tambahkan data bayi untuk melihat grafik perkembangan</p>
            </div>
        @else
            <div id="mainContent">
                <!-- Selector -->
                <div
                    class="mb-8 flex flex-col sm:flex-row items-center justify-center sm:gap-4 gap-2 bg-gradient-to-r from-purple-100 to-pink-100 rounded-2xl p-4 sm:p-6 text-center sm:text-left">

                    <label for="bayiSelect"
                        class="text-base sm:text-lg font-semibold text-gray-700 flex items-center justify-center sm:justify-start gap-2">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Pilih Bayi:
                    </label>

                    <select id="bayiSelect"
                        class="w-full sm:w-auto text-base sm:text-lg border-2 border-purple-300 rounded-xl px-4 py-2 sm:px-6 sm:py-3 bg-white focus:ring-4 focus:ring-purple-200 focus:border-purple-400 transition-all outline-none font-medium text-gray-700 cursor-pointer">
                        @foreach ($bayis as $bayi)
                            <option value="{{ $bayi->id }}">{{ $bayi->nama_bayi }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Charts Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
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
                            <canvas id="beratChart"></canvas>
                        </div>
                        <div class="mt-4 text-center">
                            <p class="text-sm text-gray-600">Satuan: Kilogram (kg)</p>
                        </div>
                    </div>

                    <!-- Tinggi Badan Chart -->
                    <div
                        class="chart-container bg-gradient-to-br from-green-50 to-emerald-100 p-6 rounded-2xl shadow-lg">
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
                            <canvas id="tinggiChart"></canvas>
                        </div>
                        <div class="mt-4 text-center">
                            <p class="text-sm text-gray-600">Satuan: Sentimeter (cm)</p>
                        </div>
                    </div>
                </div>

                <!-- Stats Summary -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4" id="statsContainer">
                    <div class="bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl p-5 text-center">
                        <p class="text-sm text-purple-700 font-medium mb-1">Total Pencatatan</p>
                        <p class="text-3xl font-bold text-purple-900" id="totalRecords">0</p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl p-5 text-center">
                        <p class="text-sm text-blue-700 font-medium mb-1">Berat Terkini</p>
                        <p class="text-3xl font-bold text-blue-900" id="latestWeight">- kg</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-100 to-green-200 rounded-xl p-5 text-center">
                        <p class="text-sm text-green-700 font-medium mb-1">Tinggi Terkini</p>
                        <p class="text-3xl font-bold text-green-900" id="latestHeight">- cm</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
    </div>

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
                    © 2025 GEMAS All rights reserved.
                </p>
        </div>
    </footer>
    <!-- end footer -->

    <!-- Scripts grafik -->
    <script>
        const dataPerkembangan = @json($dataPerkembangan);

        const bayiSelect = document.getElementById('bayiSelect');
        let beratChart, tinggiChart;
        

        function updateStats(data) {
            if (!data || data.length === 0) return;

            document.getElementById('totalRecords').textContent = data.length;
            document.getElementById('latestWeight').textContent = data[data.length - 1].berat + ' kg';
            document.getElementById('latestHeight').textContent = data[data.length - 1].tinggi + ' cm';
        }
        

        function renderChart(bayiId) {
            const data = dataPerkembangan[bayiId];
            if (!data || data.length === 0) return;

            const labels = data.map(item => item.tanggal_catat);
            const beratData = data.map(item => item.berat);
            const tinggiData = data.map(item => item.tinggi);

            updateStats(data);
            formatdate = dateStr => {
                const options = {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                };
                return new Date(dateStr).toLocaleDateString('id-ID', options);
            };

            labels.forEach((label, index) => {
                labels[index] = formatdate(label);
            });

            if (beratChart) beratChart.destroy();
            if (tinggiChart) tinggiChart.destroy();

            const ctxBerat = document.getElementById('beratChart').getContext('2d');
            const ctxTinggi = document.getElementById('tinggiChart').getContext('2d');

            const chartConfig = {
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
                            color: 'rgba(0, 0, 0, 0.05)',
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

            beratChart = new Chart(ctxBerat, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Berat Badan (kg)',
                        data: beratData,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 6,
                        pointHoverRadius: 10,
                        pointBackgroundColor: '#3B82F6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 3,
                        pointHoverBackgroundColor: '#2563EB',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 3,
                        borderWidth: 3
                    }]
                },
                options: chartConfig
            });

            tinggiChart = new Chart(ctxTinggi, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Tinggi Badan (cm)',
                        data: tinggiData,
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 6,
                        pointHoverRadius: 10,
                        pointBackgroundColor: '#10B981',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 3,
                        pointHoverBackgroundColor: '#059669',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 3,
                        borderWidth: 3
                    }]
                },
                options: chartConfig
            });
        };

        bayiSelect.addEventListener('change', e => renderChart(e.target.value));
        if (bayiSelect) renderChart(bayiSelect.value);
    </script>

</body>

</html>
