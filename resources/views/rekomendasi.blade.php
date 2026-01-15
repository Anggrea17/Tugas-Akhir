<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">
    <meta charset="UTF-8">
    <title>Form Rekomendasi MPASI - GEMAS</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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
            <li><a href="{{ route('rekomendasi.form') }}" class="hover:text-yellow-600">Rekomendasi MPASI</a></li>
            <li><a href="{{ route('artikel') }}" class="hover:text-yellow-600">Artikel</a></li>

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
                        <div class="mt-4 text-right">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm">
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
            <a href="{{ route('rekomendasi.form') }}" class="flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg {{ request()->routeIs('landingpg') ? 'text-yellow-700' : 'text-gray-400' }}"
                    class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 9l9-7 9 7v11a2 2 0 01-2 2h-2a2 2 0 01-2-2V12H9v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                <span>Beranda</span>
            </a>
            <a href="{{ route('rekomendasi.form') }}" class="flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V7a1 1 0 00-1-1h-6l-2-2H5a1 1 0 00-1 1v6h16zM4 15h16v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2z" />
                </svg>
                <span>Rekomendasi</span>
            </a>
            <a href="{{ route('artikel') }}" class="flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21H5a2 2 0 01-2-2V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2z" />
                </svg>
                <span>Artikel</span>
            </a>

            @auth
                <div x-data="{ open: false }" class="relative flex flex-col items-center">
                    <button @click="open = !open" class="focus:outline-none flex flex-col items-center">
                        <img src="{{ asset('bahan/pasangan.png') }}" alt="User"
                            class="w-6 h-6 rounded-full border-2 border-yellow-300 mb-1">
                        <span>Profil</span>
                    </button>

                    <div x-show="open" @click.away="open = false"
                        class="absolute bottom-full mb-2 w-48 bg-white rounded-lg shadow-xl text-gray-800 p-3 z-50 text-left"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform translate-y-2">
                        <div class="flex items-center space-x-2 mb-2">
                            <img src="{{ asset('bahan/pasangan.png') }}" alt="User" class="w-8 h-8 rounded-full">
                            <div>
                                <p class="font-semibold text-sm truncate">{{ Auth::user()->nama }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left text-red-500 hover:text-red-700 text-sm py-1 px-2 rounded">
                                Logout
                            </button>
                        </form>
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
    <header class="text-center py-10 bg-yellow-100">
        <h2 class="text-3xl font-bold text-yellow-900">Input Data MPASI</h2>
        <p class="text-gray-600 mt-2">Masukkan data untuk menghitung kebutuhan kalori bayi Anda</p>
    </header>

    <!-- Form -->
    <main class="max-w-xl mx-auto bg-white mt-10 p-8 rounded-lg shadow-md">
        @if (session('error'))
            <div class="bg-red-100 border border-red-300 text-red-600 p-3 mb-4 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('rekomendasi.hasil') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block font-semibold text-gray-700 mb-1">Berat Badan (kg)</label>
                <input type="number" name="berat" step="0.1" required
                    class="w-full border border-gray-300 p-2 rounded">
            </div>
            <div>
                <label class="block font-semibold text-gray-700 mb-1">Tinggi Badan (cm)</label>
                <input type="number" name="tinggi" step="0.1" required
                    class="w-full border border-gray-300 p-2 rounded">
            </div>
            <div>
                <label class="block font-semibold text-gray-700 mb-1">Volume ASI per hari (ml)</label>
                <input type="number" name="volume_asi" step="1" required
                    class="w-full border border-gray-300 p-2 rounded">
            </div>
            <div class="text-center">
                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded">
                    Hitung Rekomendasi
                </button>
            </div>
        </form>
    </main>

    <!-- footer -->
    <footer class="bg-white text-brown-800 py-10 px-6">
        <div class="max-w-6xl mx-auto grid md:grid-cols-3 gap-8">
            <!-- Tentang GEMAS -->
            <div>
                <img src="{{ asset('bahan/lognav.png') }}" alt="Ikon Artikel" />
            </div>
            <div>
                <p class="text-sm mb-4">
                    GEMAS (Generasi Masa MPASI Sehat) adalah platform edukasi MPASI yang memberikan rekomendasi
                    Makanan Pendamping Air Susu Ibu berdasarkan kebutuhan kalori harian serta artikel seputar gizi &
                    MPASI.
                </p>
            </div>

            <!-- Menu Navigasi -->
            <div>
                <h3 class="text-xl font-bold mb-2">Menu</h3>
                <ul class="text-sm space-y-2">
                    <li><a href="{{ route('landingpg') }}" class="hover:underline">Beranda</a></li>
                    <li><a href="{{ route('rekomendasi.form') }}" class="hover:underline">Rekomendasi MPASI</a></li>
                    <li><a href="{{ route('artikel') }}" class="hover:underline">Artikel MPASI</a></li>
                </ul>
            </div>
        </div>

        <!-- Garis dan Hak Cipta -->
        <div class="mt-8 text-center">
            <div class="mx-auto w-48 border-t-2 border-yellow-400"></div>
            <p class="text-sm text-gray-500 mt-2">
                © 2025 GEMAS. All rights reserved.
            </p>
        </div>
    </footer>
    <!-- end footer -->

</body>

</html>
