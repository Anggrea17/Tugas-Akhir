<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">
    <meta charset="UTF-8">
    <title>Hasil Rekomendasi MPASI</title>
    @include('google-analytics') <!-- Panggil file GA di sini -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('gemas-icon.ico.png') }}">
</head>

<body class="bg-amber-50 font-sans text-gray-800" x-data="modalData()">

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
                <li class="relative" x-data="{ open: false }">
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
                <a href="{{ route('artikel') }}" class="flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21H5a2 2 0 01-2-2V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2z" />
                    </svg>
                    <span>Artikel</span>
                </a>
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
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 sm:h-3 sm:w-3 flex-shrink-0" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        <span class="font-medium">Logout</span>
                                    </button>
                                </form>
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

    <!-- Header -->
    <header class="text-center py-10 bg-gradient-to-br from-yellow-100 via-yellow-50 to-yellow-95 rounded-3xl  ">
        <h2 class="text-3xl font-bold text-yellow-900">Hasil Rekomendasi MPASI</h2>
        <p class="text-gray-600 mt-2">Berikut pembagian kebutuhan kalori minimum untuk bayi berdasarkan data yang
            dimasukkan
        </p>
    </header>
    <!-- Error Message -->
    @if ($errors->has('usia'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
            class="mb-6 flex items-start gap-3 p-4 rounded-xl border-l-4 border-red-500 bg-red-50 shadow-sm">
            <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M4.93 4.93l14.14 14.14M12 22C6.48 22 2 17.52 2 12S6.48 2
                     12 2s10 4.48 10 10-10 10z" />
            </svg>
            <div class="text-sm text-red-800">
                <p class="font-semibold">Usia tidak sesuai</p>
                <p>{{ $errors->first('usia') }}</p>
            </div>
        </div>
    @endif
    <!--tampilkan status pertumbuhan sebagai notifikasi -->
    <div x-data="{ showNotif: true }" x-show="showNotif" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-4"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-4">

        @if ($status_pertumbuhan == 'Pertumbuhan dalam rentang wajar')
            <!-- NOTIFIKASI STATUS NORMAL -->
            <div
                class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl shadow-lg p-6 border-2 border-green-300 relative">
                <button @click="showNotif = false"
                    class="absolute top-4 right-4 text-green-600 hover:text-green-800 transition-colors duration-200 focus:outline-none group"
                    aria-label="Tutup notifikasi">
                    <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="flex items-start space-x-4 pr-8">
                    <div class="bg-green-500 rounded-full p-3 shadow-md flex-shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span
                                class="inline-block px-3 py-1 bg-green-500 text-white rounded-full text-xs font-bold uppercase shadow-sm">
                                Status Normal
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-green-900 mb-3">Status Pertumbuhan</h3>
                        <div class="bg-white rounded-xl p-4 shadow-sm border border-green-200">
                            <p class="text-gray-700 leading-relaxed mb-3">
                                ✅ Pertumbuhan bayi berada dalam rentang wajar sesuai usia dan jenis kelamin.
                            </p>
                            <div class="bg-green-50 rounded-lg p-3 border-l-4 border-green-500">
                                <p class="text-sm text-gray-700">
                                    <strong class="text-green-700">📊 Berat badan {{ $berat }} kg</strong>
                                    sesuai untuk usia {{ $usia }} bulan ({{ $bayi->jenis_kelamin }}).
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    💡 Rekomendasi MPASI diberikan untuk menjaga kecukupan kalori minimum harian dan
                                    mendukung
                                    pertumbuhan optimal.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($status_pertumbuhan == 'Berat badan kurang')
            <!-- NOTIFIKASI BERAT BADAN KURANG -->
            <div
                class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl shadow-lg p-6 border-2 border-red-300 relative">
                <button @click="showNotif = false"
                    class="absolute top-4 right-4 text-red-600 hover:text-red-800 transition-colors duration-200 focus:outline-none group"
                    aria-label="Tutup notifikasi">
                    <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="flex items-start space-x-4 pr-8">
                    <div class="bg-red-500 rounded-full p-3 shadow-md flex-shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span
                                class="inline-block px-3 py-1 bg-red-500 text-white rounded-full text-xs font-bold uppercase shadow-sm">
                                Perlu Perhatian Khusus
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-red-900 mb-3">Status Pertumbuhan</h3>
                        <div class="bg-white rounded-xl p-4 shadow-sm border border-red-200">
                            <p class="text-gray-700 leading-relaxed mb-3">
                                ⚠️ Berat badan bayi di bawah rentang normal.
                            </p>
                            <div class="bg-red-50 rounded-lg p-3 border-l-4 border-red-500">
                                <p class="text-sm text-gray-700">
                                    <strong class="text-red-700">📊 Berat badan {{ $berat }} kg</strong> di
                                    bawah standar untuk usia {{ $usia }} bulan ({{ $bayi->jenis_kelamin }}).
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    💡 <strong>Sangat disarankan:</strong> Pastikan MPASI mengandung kalori tinggi
                                    sesuai rekomendasi. Segera konsultasikan dengan dokter atau ahli gizi untuk
                                    penanganan lebih lanjut.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($status_pertumbuhan == 'Berat badan berlebih')
            <!-- NOTIFIKASI BERAT BADAN BERLEBIH -->
            <div
                class="bg-gradient-to-br from-yellow-50 via-yellow-100 to-yellow-200 rounded-2xl shadow-lg p-6 border-2 border-yellow-400 relative">
                <button @click="showNotif = false"
                    class="absolute top-4 right-4 text-yellow-700 hover:text-yellow-900 transition-colors duration-200 focus:outline-none group"
                    aria-label="Tutup notifikasi">
                    <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="flex items-start space-x-4 pr-8">
                    <div class="bg-yellow-500 rounded-full p-3 shadow-md flex-shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span
                                class="inline-block px-3 py-1 bg-yellow-500 text-white rounded-full text-xs font-bold uppercase shadow-sm">
                                Perlu Pemantauan
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-yellow-900 mb-3">Status Pertumbuhan</h3>
                        <div class="bg-white rounded-xl p-4 shadow-sm border border-yellow-300">
                            <p class="text-gray-700 leading-relaxed mb-3">
                                ⚠️ Berat badan bayi di atas rentang normal.
                            </p>
                            <div class="bg-yellow-50 rounded-lg p-3 border-l-4 border-yellow-600">
                                <p class="text-sm text-gray-700">
                                    <strong class="text-yellow-800">📊 Berat badan {{ $berat }} kg</strong> di
                                    atas standar untuk usia {{ $usia }} bulan ({{ $bayi->jenis_kelamin }}).
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    💡 Rekomendasi MPASI disesuaikan untuk mencukupkan kebutuhan energi tanpa
                                    berlebihan. Konsultasikan dengan dokter atau ahli gizi untuk panduan lebih lanjut.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- NOTIFIKASI DATA REFERENSI TIDAK TERSEDIA -->
            <div
                class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl shadow-lg p-6 border-2 border-gray-300 relative">
                <button @click="showNotif = false"
                    class="absolute top-4 right-4 text-gray-600 hover:text-gray-800 transition-colors duration-200 focus:outline-none group"
                    aria-label="Tutup notifikasi">
                    <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="flex items-start space-x-4 pr-8">
                    <div class="bg-gray-500 rounded-full p-3 shadow-md flex-shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span
                                class="inline-block px-3 py-1 bg-gray-500 text-white rounded-full text-xs font-bold uppercase shadow-sm">
                                Informasi
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Status Pertumbuhan</h3>
                        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
                            <p class="text-gray-700 leading-relaxed mb-3">
                                ℹ️ Data referensi pertumbuhan tidak tersedia untuk validasi.
                            </p>
                            <div class="bg-gray-50 rounded-lg p-3 border-l-4 border-gray-400">
                                <p class="text-sm text-gray-700">
                                    <strong class="text-gray-700">📊 Berat badan {{ $berat }} kg</strong> untuk
                                    usia {{ $usia }} bulan ({{ $bayi->jenis_kelamin }}).
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    💡 Rekomendasi MPASI tetap diberikan berdasarkan kebutuhan kalori. Konsultasikan
                                    dengan dokter untuk memastikan pertumbuhan bayi optimal.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div> <!-- Hasil Perhitungan -->
    <main class="max-w-4xl mx-auto bg-white mt-10 p-8 rounded-lg shadow-md space-y-6">

        <div>
            <h3 class="text-xl font-bold text-yellow-800 mb-2">📋 Data Bayi</h3>
            <!-- Dropdown pilih bayi -->
            <div class="mb-6">
                <label for="pilih_bayi" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Bayi
                </label>
                <select id="pilih_bayi" @change="handleBayiChange($event.target.value)"
                    class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Pilih Bayi --</option>
                    @foreach ($user->bayis as $b)
                        <option value="{{ $b->id }}" {{ $bayi->id == $b->id ? 'selected' : '' }}>
                            {{ $b->nama_bayi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <ul class="text-gray-700 space-y-1">
                <li><strong>Nama:</strong> {{ $bayi->nama_bayi }}</li>
                <li><strong>Tanggal Lahir:</strong> {{ \Carbon\Carbon::parse($bayi->tanggal_lahir)->format('d M Y') }}
                </li>
                <li><strong>Usia:</strong> {{ $usia }} bulan</li>
                <li><strong>Jenis Kelamin:</strong> {{ $bayi->jenis_kelamin }}</li>
                <li><strong>Berat Badan:</strong> {{ $berat }} kg</li>
                <li><strong>Tinggi Badan:</strong> {{ $tinggi }} cm</li>
                <li><strong>Jenis Susu:</strong> {{ $bayi->jenis_susu }}</li>

                {{-- tampilkan data susu sesuai jenis --}}
                @if ($bayi->jenis_susu === 'ASI')
                    <li><strong>Volume ASI:</strong> {{ $volume_asi }} ml</li>
                @elseif ($bayi->jenis_susu === 'Sufor')
                    <li><strong>Kalori per Porsi:</strong> {{ $kalori_per_porsi }} kkal</li>
                    <li><strong>Jumlah Porsi sufor per Hari:</strong> {{ $bayi->jumlah_porsi_per_hari ?? '-' }} kali
                    </li>
                @elseif ($bayi->jenis_susu === 'Mix')
                    <li><strong>Volume ASI:</strong> {{ $bayi->volume_asi ?? '-' }} ml</li>
                    <li><strong>Jumlah Porsi sufor per Hari:</strong> {{ $bayi->jumlah_porsi_per_hari ?? '-' }} kali
                    </li>
                @endif
            </ul>
        </div>

        <div>
            <h3 class="text-xl font-bold text-yellow-800 mb-2">⚖️ Perhitungan Kalori</h3>
            <ul class="text-gray-700 space-y-1">
                <li><strong>Kebutuhan Energi (EER):</strong> {{ $eer }} kkal</li>

                @if ($bayi->jenis_susu === 'ASI')
                    <li><strong>Kalori dari ASI:</strong> {{ $kalori_asi }} kkal</li>
                @elseif ($bayi->jenis_susu === 'Sufor')
                    <li><strong>Kalori dari Sufor:</strong> {{ $kalori_sufor }} kkal</li>
                @elseif ($bayi->jenis_susu === 'Mix')
                    <li><strong>Kalori dari ASI:</strong> {{ $kalori_asi }} kkal</li>
                    <li><strong>Kalori dari Sufor:</strong> {{ $kalori_sufor }} kkal</li>
                @endif

                <li><strong>Kalori dari MPASI:</strong> {{ $kalori_mpasi }} kkal</li>
            </ul>
        </div>

        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-yellow-800">
                    🍽️ Pembagian Kalori Minimum Harian & Rekomendasi MPASI
                </h3>
                <button onclick="refreshRekomendasi()"
                    class="text-sm bg-green-700 hover:bg-yellow-800 text-white px-3 py-1 rounded shadow">
                    🔄 Refresh
                </button>
            </div>

            @foreach ([
        'Pagi' => ['kalori' => $pagi, 'jam' => '08.00'],
        'Siang' => ['kalori' => $siang, 'jam' => '12.00'],
        'Malam' => ['kalori' => $malam, 'jam' => '18.00'],
        'Snack' => ['kalori' => $snack, 'jam' => '16.00'],
    ] as $waktu => $data)
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-yellow-900">
                        {{ $waktu }} ({{ $data['jam'] }}) – {{ $data['kalori'] }} kkal
                    </h4>

                    <div class="grid md:grid-cols-2 gap-4 mt-2">

                        @forelse (${'resep' . $waktu} as $mpasi)
                            <div x-data="{ open: false }"
                                class="bg-yellow-50 border border-yellow-200 p-4 rounded shadow">

                                <!-- KARTU RESEP -->
                                <h4 class="font-semibold text-lg text-yellow-900">
                                    {{ $mpasi->nama_menu }}
                                </h4>

                                <button @click="open = true"
                                    class="mt-2 text-sm text-white bg-yellow-600 hover:bg-yellow-700 px-4 py-1 rounded">
                                    Lihat Detail
                                </button>

                                <!-- Modal Detail Resep -->
                                <div x-show="open" x-transition
                                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl w-11/12 md:w-2/3 p-6 shadow-2xl relative transform transition-all duration-300 overflow-y-auto max-h-full"
                                        x-show="open" x-transition:enter="transition transform duration-300"
                                        x-transition:enter-start="scale-75 opacity-0"
                                        x-transition:enter-end="scale-100 opacity-100"
                                        x-transition:leave="transition transform duration-200"
                                        x-transition:leave-start="scale-100 opacity-100"
                                        x-transition:leave-end="scale-75 opacity-0">

                                        <!-- Gambar & Nama Menu -->
                                        <div class="text-center mb-6">
                                            @if (!empty($mpasi['gambar']))
                                                <img src="{{ asset($mpasi->gambar) }}"
                                                    alt="gambar menu {{ $mpasi->nama_menu }}"
                                                    class="w-40 h-40 md:w-48 md:h-48 object-cover rounded-xl shadow-md mx-auto">
                                            @endif

                                            <h2 class="mt-4 text-3xl font-extrabold text-yellow-800">
                                                {{ $mpasi['nama_menu'] }}
                                            </h2>
                                        </div>

                                        <!-- Informasi Gizi -->
                                        <div class="mb-4 p-4 bg-yellow-100 rounded-xl">
                                            <h3 class="text-right font-semibold text-blue-700 text-lg mb-2">
                                                Untuk {{ $mpasi['porsi'] }} porsi
                                            </h3>

                                            <h3 class="text-2xl font-bold text-green-600 mb-3">
                                                📊 Informasi Gizi Per Porsi:
                                            </h3>

                                            <div class="grid grid-cols-2 gap-3 text-lg text-gray-800">
                                                <div>
                                                    <span class="font-bold">Total Energi:</span>
                                                    {{ empty($mpasi['energi']) ? '-' : $mpasi['energi'] . ' kkal' }}
                                                </div>

                                                <div>
                                                    <span class="font-bold">Karbohidrat:</span>
                                                    {{ empty($mpasi['karbohidrat']) ? '-' : $mpasi['karbohidrat'] . ' gram' }}
                                                </div>

                                                <div>
                                                    <span class="font-bold">Protein:</span>
                                                    {{ empty($mpasi['protein']) ? '-' : $mpasi['protein'] . ' gram' }}
                                                </div>

                                                <div>
                                                    <span class="font-bold">Lemak:</span>
                                                    {{ empty($mpasi['lemak']) ? '-' : $mpasi['lemak'] . ' gram' }}
                                                </div>

                                                <div>
                                                    <span class="font-bold">Zat Besi:</span>
                                                    {{ empty($mpasi['zat_besi']) ? '-' : $mpasi['zat_besi'] . ' mg' }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Bahan-bahan -->
                                        <div class="mb-4">
                                            <h3 class="text-2xl font-semibold text-pink-600 mb-3">
                                                🧺 Bahan-bahan:
                                            </h3>

                                            <ul class="list-disc list-inside text-lg text-gray-800 space-y-2">
                                                @forelse ($mpasi->bahans as $bahan)
                                                    <li>{{ $bahan->takaran }} {{ $bahan->bahan }}</li>
                                                @empty
                                                    <li>-</li>
                                                @endforelse
                                            </ul>
                                        </div>

                                        <!-- Langkah-langkah -->
                                        <div>
                                            <h3 class="text-2xl font-semibold text-blue-600 mb-3">
                                                👣 Langkah-langkah:
                                            </h3>

                                            <ol class="list-decimal list-inside text-lg text-gray-800 space-y-3">
                                                @forelse ($mpasi->langkahs as $langkah)
                                                    <li>{{ $langkah->langkah }}</li>
                                                @empty
                                                    <li>-</li>
                                                @endforelse
                                            </ol>
                                        </div>

                                        <!-- Tombol Tutup -->
                                        <button @click="open = false"
                                            class="absolute top-3 right-3 text-3xl text-red-400 hover:text-red-600 font-bold"
                                            aria-label="Tutup">
                                            &times;
                                        </button>
                                    </div>
                                </div>

                            </div>

                        @empty
                            <div
                                class="md:col-span-2 text-center bg-red-50 border border-red-200 text-red-700 p-4 rounded">
                                ❌ Tidak ada data resep yang tersedia untuk {{ strtolower($waktu) }}
                            </div>
                        @endforelse

                    </div>
                </div>
            @endforeach
        </div>
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

    <!-- Modal lengkapi data bayi -->
    <div x-show="modalLengkapiBayi" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-800 bg-opacity-50 z-50 flex justify-center items-center">

        <!-- Wrapper scroll -->
        <div class="absolute inset-0 overflow-y-auto">
            <div class="flex min-h-flex justify-center items-center overflow-y-auto py-10 px-4 sm:px-6 lg:px-8">
                <!-- Konten Modal -->
                <div class="relative bg-white rounded-xl shadow-lg w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto"
                    @click.away="modalLengkapiBayi = false" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95">
                    <div
                        class="flex justify-between items-center mb-4 sticky top-0 bg-white z-10 pb-2 border-b border-gray-200">
                        <h2 class="text-lg font-bold">Lengkapi Data Bayi</h2>
                        <button @click="modalLengkapiBayi = false"
                            class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
                    </div>

                    <!--error message-->
                    @if (session('age_warning'))
                        <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-800 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <strong>Peringatan:</strong>
                            </div>
                            <p class="mt-1">{{ session('age_warning') }}</p>
                            <p class="text-sm mt-1">Usia bayi: {{ session('bayi_usia') }} bulan</p>
                        </div>
                    @endif
                    <form @submit.prevent="submitBayiData()">
                        <input type="hidden" x-ref="bayiId">

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bayi</label>
                            <input type="text" x-ref="namaBayi" x-model="formData.nama_bayi"
                                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:outline-none focus:ring-yellow-400">
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                            <select x-ref="jenisKelamin" x-model="formData.jenis_kelamin"
                                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:outline-none focus:ring-yellow-400">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>`

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" x-ref="tanggalLahir" x-model="formData.tanggal_lahir"
                                @change="hitungUmur()"
                                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:outline-none focus:ring-yellow-400">
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Umur (bulan)</label>
                            <input type="text" x-model="formData.umur" readonly
                                class="border p-2 rounded w-full focus:ring-2 focus:outline-none focus:ring-yellow-400">
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Berat (kg)</label>
                            <input type="number" step="0.1" min="2" max="15" x-ref="berat"
                                x-model="formData.berat" min="0"
                                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:outline-none focus:ring-yellow-400">
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tinggi (cm)</label>
                            <input type="number" step="0.1" min="40" max="100" x-ref="tinggi"
                                x-model="formData.tinggi" min="0"
                                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:outline-none focus:ring-yellow-400">
                        </div>
                        <!-- pilih jenis susu -->
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Susu</label>
                            <select x-ref="jenisSusu" x-model="formData.jenis_susu"
                                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:outline-none focus:ring-yellow-400">
                                <option value="">-- Pilih Jenis Susu --</option>
                                <option value="ASI">ASI</option>
                                <option value="Sufor">Susu Formula</option>
                                <option value="Mix">Campuran (ASI dan Susu Formula)</option>
                            </select>
                            <!-- Volume ASI -->
                            <div class="mb-3"
                                x-show="formData.jenis_susu === 'ASI' || formData.jenis_susu === 'Mix'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Volume ASI (ml per
                                    hari)</label>
                                <input type="number" x-ref="volumeAsi" x-model="formData.volume_asi" min="0"
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:outline-none focus:ring-yellow-400">
                            </div>

                            <!-- Kalori Sufor -->
                            <div class="mb-3"
                                x-show="formData.jenis_susu === 'Sufor' || formData.jenis_susu === 'Mix'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kalori per Porsi
                                    (kkal)</label>
                                <input type="number" x-ref="kaloriPerPorsi" x-model="formData.kalori_per_porsi"
                                    min="0"
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:outline-none focus:ring-yellow-400">
                            </div>

                            <!-- Jumlah Porsi -->
                            <div class="mb-3"
                                x-show="formData.jenis_susu === 'Sufor' || formData.jenis_susu === 'Mix'">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Porsi per
                                    Hari</label>
                                <input type="number" x-ref="jumlahPorsi" x-model="formData.jumlah_porsi_per_hari"
                                    min="0"
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:outline-none focus:ring-yellow-400">
                            </div>

                            <div class="flex justify-end space-x-2 mt-6">
                                <button type="button" @click="modalLengkapiBayi = false"
                                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition-colors">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-white rounded-lg transition-colors">
                                    Simpan
                                </button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function modalData() {
            return {
                modalLengkapiBayi: false,
                formData: {
                    nama_bayi: '',
                    tanggal_lahir: '',
                    jenis_kelamin: '',
                    umur: '',
                    berat: '',
                    tinggi: '',
                    volume_asi: '',
                    jenis_susu: '',
                    kalori_per_porsi: '',
                    jumlah_porsi_per_hari: ''

                },

                // Hitung umur otomatis dari tanggal lahir
                hitungUmur() {
                    if (!this.formData.tanggal_lahir) {
                        this.formData.umur = '';
                        this.hideAgeWarning();
                        return;
                    }

                    const lahir = new Date(this.formData.tanggal_lahir);
                    const today = new Date();

                    // kalau input tanggal lahir lebih dari hari ini
                    if (lahir > today) {
                        this.formData.umur = '';
                        this.showAgeWarning(null, '⚠️ Tanggal lahir tidak valid. Bayi belum lahir 🙂');
                        return;
                    }

                    let tahun = today.getFullYear() - lahir.getFullYear();
                    let bulan = today.getMonth() - lahir.getMonth();
                    let hari = today.getDate() - lahir.getDate();

                    if (hari < 0) bulan--;
                    if (bulan < 0) {
                        tahun--;
                        bulan += 12;
                    }

                    const totalBulan = tahun * 12 + bulan;
                    this.formData.umur = totalBulan + " bulan";

                    // Validasi usia untuk GEMAS
                    if (totalBulan < 6) {
                        this.showAgeWarning(totalBulan,
                            '⚠️ GEMAS belum bisa untuk bayi di bawah 6 bulan. Rekomendasi MPASI dimulai dari usia 6 bulan ke atas.'
                        );
                    } else if (totalBulan > 12) {
                        this.showAgeWarning(totalBulan,
                            '⚠️ GEMAS hanya untuk bayi 6-12 bulan. Untuk anak di atas 12 bulan, konsultasikan dengan ahli gizi.'
                        );
                    } else {
                        this.hideAgeWarning();
                    }
                },

                showAgeWarning(usia, message) {
                    this.hideAgeWarning();

                    const warningDiv = document.createElement('div');
                    warningDiv.id = 'age-warning';
                    warningDiv.className =
                        'mt-2 p-3 bg-yellow-100 border border-yellow-400 text-yellow-800 rounded-lg text-sm';
                    warningDiv.innerHTML = message;

                    // Tempelkan setelah input tanggal lahir
                    const inputTanggal = this.$refs.tanggalLahir;
                    inputTanggal.insertAdjacentElement('afterend', warningDiv);
                },

                hideAgeWarning() {
                    const existingWarning = document.getElementById('age-warning');
                    if (existingWarning) {
                        existingWarning.remove();
                    }
                },

                handleBayiChange(bayiId) {
                    if (!bayiId) return;

                    fetch(`/rekomendasi?bayi_id=${bayiId}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.lengkap) {
                                window.location.href = data.redirect;
                            } else {
                                // isi modal dengan data bayi yang ada
                                this.formData = {
                                    nama_bayi: data.bayi?.nama_bayi || '',
                                    tanggal_lahir: data.bayi?.tanggal_lahir || '',
                                    jenis_kelamin: data.bayi?.jenis_kelamin || '',
                                    umur: '',
                                    berat: data.bayi?.berat || '',
                                    tinggi: data.bayi?.tinggi || '',
                                    volume_asi: data.bayi?.volume_asi || ''
                                };
                                this.$refs.bayiId.value = data.bayi_id;

                                if (this.formData.tanggal_lahir) this.hitungUmur();
                                this.modalLengkapiBayi = true;
                            }
                        })
                        .catch(err => {
                            console.error('Error fetching bayi data:', err);
                            alert('Terjadi kesalahan saat mengambil data bayi. Silakan coba lagi.');
                        });
                },

                resetForm() {
                    this.formData = {
                        nama_bayi: '',
                        tanggal_lahir: '',
                        jenis_kelamin: '',
                        umur: '',
                        berat: '',
                        tinggi: '',
                        volume_asi: '',
                        jenis_susu: '',
                        kalori_per_porsi: '',
                        jumlah_porsi_per_hari: ''
                    };
                    if (this.$refs) {
                        Object.keys(this.$refs).forEach(key => {
                            if (this.$refs[key]?.classList)
                                this.$refs[key].classList.remove('border-red-500');
                        });
                    }
                },

                submitBayiData() {
                    const bayiId = this.$refs.bayiId.value;

                    // validasi dasar
                    if (!this.formData.nama_bayi || !this.formData.tanggal_lahir || !this.formData.jenis_kelamin) {
                        alert('Nama, tanggal lahir, dan jenis kelamin wajib diisi.');
                        return;
                    }

                    if (!this.formData.jenis_susu) {
                        alert('Pilih jenis susu terlebih dahulu.');
                        return;
                    }

                    // validasi sesuai jenis susu
                    if (this.formData.jenis_susu === 'ASI' || this.formData.jenis_susu === 'Mix') {
                        if (!this.formData.volume_asi || this.formData.volume_asi <= 0) {
                            alert('Volume ASI harus diisi dan lebih dari 0.');
                            return;
                        }
                    }

                    if (this.formData.jenis_susu === 'Sufor' || this.formData.jenis_susu === 'Mix') {
                        if (!this.formData.kalori_per_porsi || !this.formData.jumlah_porsi_per_hari) {
                            alert('Kalori per porsi dan jumlah porsi per hari harus diisi.');
                            return;
                        }
                    }

                    // kirim data ke backend
                    fetch(`/bayi/update/${bayiId}`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    "content"),
                            },
                            body: JSON.stringify(this.formData),
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.modalLengkapiBayi = false;
                                window.location.href = data.redirect;
                            } else {
                                alert(data.message || "Gagal menyimpan data bayi.");
                            }
                        })
                        .catch(err => {
                            console.error("Error:", err);
                            alert("Terjadi kesalahan server.");
                        });
                }
            }
        }
    </script>
    <script>
        function refreshRekomendasi() {
            const urlParams = new URLSearchParams(window.location.search);
            const bayiId = urlParams.get('bayi_id');
            let baseUrl = window.location.origin + window.location.pathname;
            let newQuery = '?refresh=' + new Date().getTime();

            if (bayiId) {
                newQuery += '&bayi_id=' + bayiId;
            }

            window.location.href = baseUrl + newQuery;
        }
    </script>
</body>

</html>
