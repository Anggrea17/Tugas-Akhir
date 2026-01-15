<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">
    <meta charset="UTF-8">
    <title>Detail Artikel - GEMAS</title>
    @include('google-analytics')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <script src="//unpkg.com/alpinejs" defer></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <style>
        .trix-content figure {
            display: flex;
            justify-content: center;
            margin: 1.5rem 0;
        }

        .trix-content figure img {
            max-width: 100%;
            height: auto;
        }

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

        /* TTS Styles */
        .tts-floating-controls {
            position: fixed;
            bottom: 80px;
            right: 20px;
            z-index: 40;
        }

        @media (max-width: 768px) {
            .tts-floating-controls {
                bottom: 90px;
            }
        }

        .tts-button {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .tts-button:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        .tts-button.speaking {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { 
                opacity: 1; 
                box-shadow: 0 0 0 0 rgba(234, 179, 8, 0.7);
            }
            50% { 
                opacity: 0.8; 
                box-shadow: 0 0 0 10px rgba(234, 179, 8, 0);
            }
        }

        .tts-progress-bar {
            transition: width 0.3s ease;
        }

        .tts-panel {
            max-width: 320px;
            backdrop-filter: blur(10px);
        }

        .reading-highlight {
            background-color: rgba(234, 179, 8, 0.2);
            transition: background-color 0.3s ease;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-yellow-50 text-gray-800" x-data="ttsArticleController()">

    <!-- Navbar untuk layar besar (desktop) -->
    <nav class="hidden md:flex bg-yellow-100 shadow p-4 justify-between items-center rounded-b-xl px-8"
        x-data="{ open: false, profileOpen: false }">
        <div class="flex items-center justify-between w-full md:w-auto">
            <a href="{{ route('landingpg') }}" class="flex items-center space-x-2">
                <img src="{{ asset('bahan/lognav.png') }}" alt="Logo" class="h-10">
            </a>
        </div>

        <ul class="flex flex-row items-center space-x-6 font-medium">
            <li>
                @auth
                    <a href="{{ route('rekomendasi') }}"
                        class="text-black-600 font-semibold hover:text-yellow-600 transition-colors duration-200">
                        Rekomendasi MPASI
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="text-black-600 font-semibold hover:text-yellow-600 transition-colors duration-200">
                        Rekomendasi MPASI
                    </a>
                @endauth
            </li>
            @auth
                <li>
                    <a href="{{ route('perkembangan.index') }}"
                        class="text-black-600 font-semibold hover:text-yellow-600 transition-colors duration-200">
                        Perkembangan Bayi
                    </a>
                </li>
            @endauth
            <li><a href="{{ route('artikel') }}" class="hover:text-yellow-600">Artikel</a></li>
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
                        <div class="mt-4 flex justify-end gap-2">
                            @if (Auth::check() && Auth::user()->role != 'admin')
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center gap-1 px-4 py-2 bg-indigo-500 text-white text-sm rounded-lg shadow hover:bg-indigo-600 transition whitespace-nowrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.232 5.232l3.536 3.536M9 11l6-6m2 2L9 17H7v-2l8-8z" />
                                    </svg>
                                    Edit Profile
                                </a>
                            @endif

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
    <div class="fixed bottom-0 left-0 right-0 z-50 bg-yellow-100 border-t border-yellow-300 text-brown-800 shadow md:hidden">
        <div class="flex justify-around py-2 text-sm">
            <a href="{{ route('landingpg') }}" class="flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                    </svg>
                    <span>Rekomendasi</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1 text-gray-700" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V7a1 1 0 00-1-1h-6l-2-2H5a1 1 0 00-1 1v6h16zM4 15h16v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2z" />
                    </svg>
                    <span>Rekomendasi</span>
                </a>
            @endauth
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
                        class="fixed sm:absolute bottom-16 sm:bottom-full right-1 sm:right-0 sm:mb-2 w-80 sm:w-48 bg-white rounded-lg shadow-xl text-gray-800 p-4 sm:p-3 z-50 text-left max-w-[calc(100vw-0.5rem)] sm:max-w-none"
                        x-transition>
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

                        <div class="flex gap-3 sm:flex-col sm:space-y-1 sm:gap-0">
                            @if (Auth::check() && Auth::user()->role != 'admin')
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center justify-center gap-2 flex-1 sm:w-full px-4 py-3 sm:px-2 sm:py-1.5 bg-indigo-500 text-white text-base sm:text-xs rounded-xl sm:rounded hover:bg-indigo-600 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-3 sm:w-3 flex-shrink-0"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.232 5.232l3.536 3.536M9 11l6-6m2 2L9 17H7v-2l8-8z" />
                                    </svg>
                                    <span class="hidden sm:inline">Edit Profile</span>
                                    <span class="sm:hidden font-medium">Edit</span>
                                </a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="flex-1 sm:w-full">
                                @csrf
                                <button type="submit"
                                    class="flex items-center justify-center gap-2 w-full px-4 py-3 sm:px-2 sm:py-1.5 bg-red-500 text-white text-base sm:text-xs rounded-xl sm:rounded hover:bg-red-600 transition-colors duration-200">
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

    <!-- Floating TTS Controls -->
    <div class="tts-floating-controls" x-cloak x-show="showControls" x-transition>
        <div class="tts-panel bg-white rounded-2xl shadow-2xl p-4 border border-yellow-200">
            <!-- Header -->
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15.414a2 2 0 001.414.586h2.172a2 2 0 001.414-.586l2.828-2.828a2 2 0 000-2.828L10.586 7.93a2 2 0 00-2.828 0L5.93 9.757a2 2 0 00-.344 2.657z" />
                    </svg>
                    <span class="font-semibold text-gray-800 text-sm">Pembacaan Artikel</span>
                </div>
                <button @click="showControls = false" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Progress Bar -->
            <div class="mb-3">
                <div class="bg-gray-200 rounded-full h-2 overflow-hidden">
                    <div class="tts-progress-bar bg-yellow-500 h-full rounded-full" :style="`width: ${progress}%`"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span x-text="currentTime"></span>
                    <span x-text="totalTime"></span>
                </div>
            </div>

            <!-- Control Buttons -->
            <div class="flex items-center justify-center gap-3">
                <!-- Previous -->
                <button @click="previousSection()" class="p-2 rounded-full hover:bg-gray-100 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0019 16V8a1 1 0 00-1.6-.8l-5.333 4zM4.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0011 16V8a1 1 0 00-1.6-.8l-5.334 4z" />
                    </svg>
                </button>

                <!-- Play/Pause -->
                <button @click="togglePlayPause()" 
                    class="tts-button p-4 rounded-full bg-yellow-500 hover:bg-yellow-600 text-white transition-all"
                    :class="{ 'speaking': isPlaying }">
                    <svg x-show="!isPlaying" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-6.586-4.39A1 1 0 007 7.618v8.764a1 1 0 001.166.986l6.586-1.316a1 1 0 00.834-.986V12.154a1 1 0 00-.834-.986z" />
                    </svg>
                    <svg x-show="isPlaying" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6" />
                    </svg>
                </button>

                <!-- Next -->
                <button @click="nextSection()" class="p-2 rounded-full hover:bg-gray-100 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.933 12.8a1 1 0 000-1.6L6.6 7.2A1 1 0 005 8v8a1 1 0 001.6.8l5.333-4zM19.933 12.8a1 1 0 000-1.6l-5.333-4A1 1 0 0013 8v8a1 1 0 001.6.8l5.333-4z" />
                    </svg>
                </button>

                <!-- Stop -->
                <button @click="stopReading()" class="p-2 rounded-full hover:bg-gray-100 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Speed Control -->
            <div class="mt-3 pt-3 border-t border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-gray-600">Kecepatan</span>
                    <span class="text-xs font-semibold text-gray-800" x-text="speed + 'x'"></span>
                </div>
                <input type="range" min="0.5" max="2" step="0.25" x-model="speed" @input="updateSpeed()"
                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
            </div>
        </div>
    </div>

    <!-- Main TTS Button (Always Visible) -->
    <div class="fixed bottom-24 right-6 z-40 md:bottom-8">
        <button @click="showControls = !showControls" 
            class="tts-button bg-yellow-500 hover:bg-yellow-600 text-white p-4 rounded-full shadow-lg"
            :class="{ 'speaking': isPlaying }">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15.414a2 2 0 001.414.586h2.172a2 2 0 001.414-.586l2.828-2.828a2 2 0 000-2.828L10.586 7.93a2 2 0 00-2.828 0L5.93 9.757a2 2 0 00-.344 2.657z" />
            </svg>
        </button>
    </div>

    <main class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-8">
            <a href="{{ route('artikel') }}"
                class="inline-flex items-center text-yellow-700 hover:text-yellow-900 transition-all duration-200 group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke daftar artikel
            </a>
        </div>

        <!-- Article Header -->
        <article class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Hero Image -->
            @if ($artikel->gambar)
                <div class="w-full h-96 overflow-hidden">
                    <img src="{{ $artikel->gambar_url }}" alt="{{ $artikel->judul }}"
                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                </div>
            @endif

            <!-- Content Container -->
            <div class="px-6 sm:px-10 lg:px-16 py-10">
                <!-- Title -->
                <h1 class="text-4xl sm:text-5xl font-bold text-yellow-900 mb-6 leading-tight" id="article-title">
                    {{ $artikel->judul }}
                </h1>

                <!-- Meta Information -->
                <div class="flex flex-wrap items-center gap-6 pb-8 mb-8 border-b border-gray-200">
                    <div class="flex items-center text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-700" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-base">{{ $artikel->created_at->format('d M Y') }}</span>
                    </div>

                    @if (isset($artikel->kategori))
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 text-sm font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                {{ $artikel->kategori->nama_kategori }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Article Content -->
                <div class="trix-content prose prose-lg max-w-none mb-12" id="article-content" style="text-align: justify; text-justify: inter-word;">
                    {!! $artikel->isi !!}
                </div>

                <!-- Sources Section -->
                @if (isset($artikel->sumber) && !empty($artikel->sumber))
                    <div class="mt-12 pt-10 border-t border-gray-200" id="article-sources">
                        <h3 class="text-2xl font-semibold text-gray-900 mb-6 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-yellow-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Sumber Referensi
                        </h3>
                        <div class="bg-gradient-to-br from-gray-50 to-yellow-50 rounded-xl p-6 border border-gray-100">
                            @php
                                $sources = is_string($artikel->sumber)
                                    ? explode("\n", $artikel->sumber)
                                    : (is_array($artikel->sumber)
                                        ? $artikel->sumber
                                        : [$artikel->sumber]);
                            @endphp

                            @if (count($sources) > 1)
                                <ol class="list-decimal list-inside space-y-3 text-gray-700">
                                    @foreach ($sources as $source)
                                        @if (trim($source))
                                            <li class="break-words text-base leading-relaxed">
                                                @if (filter_var(trim($source), FILTER_VALIDATE_URL))
                                                    <a href="{{ trim($source) }}" target="_blank"
                                                        class="text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                                                        {{ trim($source) }}
                                                    </a>
                                                @else
                                                    {{ trim($source) }}
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                </ol>
                            @else
                                <p class="text-base text-gray-700 break-words leading-relaxed">
                                    @if (filter_var(trim($artikel->sumber), FILTER_VALIDATE_URL))
                                        <a href="{{ trim($artikel->sumber) }}" target="_blank"
                                            class="text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                                            {{ trim($artikel->sumber) }}
                                        </a>
                                    @else
                                        {{ $artikel->sumber }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </article>

        <!-- Related Articles -->
        @if ($relatedArtikels->isNotEmpty())
            <div class="mt-16">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold text-yellow-900">Artikel Lainnya</h2>
                    <a href="{{ route('artikel') }}" 
                       class="text-yellow-700 hover:text-yellow-900 font-medium flex items-center group transition-colors">
                        Lihat Semua
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($relatedArtikels as $relatedArtikel)
                      <a href="{{ route('artikel.show', $relatedArtikel->slug) }}">
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 group">
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ $relatedArtikel->gambar_url }}" 
                                     alt="{{ $relatedArtikel->judul }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3 line-clamp-2 group-hover:text-yellow-900 transition-colors">
                                    {{ $relatedArtikel->judul }}
                                </h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ Str::limit(strip_tags($relatedArtikel->isi), 80) }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-500 text-xs">
                                        {{ $relatedArtikel->created_at->format('d M Y') }}
                                    </span>
                                   <span
                                       class="text-yellow-700 hover:text-yellow-900 font-medium text-sm flex items-center group/link">
                                        Baca
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transform group-hover/link:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                   </span>
                                </div>
                            </div>
                        </div>
                         </a>
                    @endforeach
                </div>
            </div>
        @endif
    </main>

    <!-- footer -->
    <footer class="bg-white text-brown-800 py-10 px-6">
        <div class="max-w-6xl mx-auto grid md:grid-cols-3 gap-8">
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

        <div class="mt-8 text-center">
            <div class="mx-auto w-48 border-t-2 border-yellow-400"></div>
            <p class="text-base text-gray-600">
                © 2025 GEMAS All rights reserved.
            </p>
        </div>
    </footer>

    <script>
        function ttsArticleController() {
            return {
                isPlaying: false,
                showControls: false,
                speed: 1,
                progress: 0,
                currentTime: '0:00',
                totalTime: '0:00',
                currentSection: 0,
                sections: [],
                utterance: null,
                isInitialized: false,
                
                init() {
                    // Cleanup any existing speech synthesis
                    this.forceStopAllSpeech();
                    
                    this.extractArticleSections();
                    this.setupSpeechSynthesis();
                    this.setupPageUnloadHandler();
                    this.isInitialized = true;
                },
                
                setupPageUnloadHandler() {
                    // Stop speech when navigating away
                    window.addEventListener('beforeunload', () => {
                        this.forceStopAllSpeech();
                    });
                    
                    // Stop speech when page visibility changes
                    document.addEventListener('visibilitychange', () => {
                        if (document.hidden && this.isPlaying) {
                            this.stopReading();
                        }
                    });
                },
                
                forceStopAllSpeech() {
                    try {
                        speechSynthesis.cancel();
                        speechSynthesis.pause();
                        
                        // Remove all highlights
                        document.querySelectorAll('.reading-highlight').forEach(el => {
                            el.classList.remove('reading-highlight');
                        });
                        
                        this.isPlaying = false;
                        this.currentSection = 0;
                        this.progress = 0;
                    } catch (e) {
                        console.warn('Error stopping speech:', e);
                    }
                },

                extractArticleSections() {
                    const title = document.getElementById('article-title');
                    const content = document.getElementById('article-content');
                    
                    this.sections = [];
                    
                    // Add title
                    if (title) {
                        this.sections.push({
                            element: title,
                            text: 'Judul artikel: ' + title.textContent.trim()
                        });
                    }
                    
                    // Add content paragraphs
                    if (content) {
                        // Ambil semua elemen teks dari konten
                        const elements = content.querySelectorAll('p, h1, h2, h3, h4, h5, h6, li, div, span');
                        
                        // Filter untuk mendapatkan elemen yang memiliki teks langsung (bukan parent)
                        const validElements = [];
                        elements.forEach(el => {
                            // Cek apakah elemen ini memiliki teks langsung (tidak hanya dari child)
                            const directText = Array.from(el.childNodes)
                                .filter(node => node.nodeType === Node.TEXT_NODE)
                                .map(node => node.textContent.trim())
                                .join(' ');
                            
                            const fullText = el.textContent.trim();
                            
                            // Jika ada teks langsung atau elemen tidak memiliki child dengan teks
                            if (fullText && fullText.length > 20) {
                                // Cek apakah ini bukan duplikat dari parent/child
                                const isDuplicate = validElements.some(existing => 
                                    existing.text === fullText || 
                                    existing.text.includes(fullText) || 
                                    fullText.includes(existing.text)
                                );
                                
                                if (!isDuplicate) {
                                    validElements.push({
                                        element: el,
                                        text: fullText
                                    });
                                }
                            }
                        });
                        
                        // Jika tidak ada elemen yang terdeteksi, ambil semua teks dari content
                        if (validElements.length === 0) {
                            const allText = content.textContent.trim();
                            if (allText) {
                                // Split menjadi kalimat-kalimat
                                const sentences = allText.split(/[.!?]+/).filter(s => s.trim().length > 20);
                                sentences.forEach(sentence => {
                                    this.sections.push({
                                        element: content,
                                        text: sentence.trim() + '.'
                                    });
                                });
                            }
                        } else {
                            this.sections.push(...validElements);
                        }
                    }
                    
                    console.log('Total sections found:', this.sections.length);
                    console.log('Sections:', this.sections);
                    
                    this.calculateTotalTime();
                },

                calculateTotalTime() {
                    const totalChars = this.sections.reduce((sum, section) => sum + section.text.length, 0);
                    const estimatedSeconds = Math.ceil(totalChars / 15); // ~15 chars per second
                    this.totalTime = this.formatTime(estimatedSeconds);
                },

                setupSpeechSynthesis() {
                    if ('speechSynthesis' in window) {
                        // Ensure voices are loaded
                        speechSynthesis.getVoices();
                        window.speechSynthesis.onvoiceschanged = () => {
                            speechSynthesis.getVoices();
                        };
                    } else {
                        console.warn('Speech Synthesis tidak didukung di browser ini');
                    }
                },

                togglePlayPause() {
                    if (this.isPlaying) {
                        this.pauseReading();
                    } else {
                        this.startReading();
                    }
                },

                startReading() {
                    if (this.sections.length === 0) {
                        alert('Tidak ada konten untuk dibacakan');
                        return;
                    }
                    
                    // Stop any existing speech first
                    this.forceStopAllSpeech();
                    
                    // Small delay to ensure previous speech is fully stopped
                    setTimeout(() => {
                        this.isPlaying = true;
                        this.showControls = true;
                        this.readSection(this.currentSection);
                    }, 100);
                },

readSection(index) {
                    if (index >= this.sections.length || !this.isPlaying) {
                        this.stopReading();
                        return;
                    }

                    const section = this.sections[index];
                    
                    // Highlight current section
                    this.highlightSection(section.element);
                    
                    // Cancel any existing utterance
                    speechSynthesis.cancel();
                    
                    // Clear any existing interval
                    if (this.progressInterval) {
                        clearInterval(this.progressInterval);
                    }
                    
                    // Create new utterance
                    this.utterance = new SpeechSynthesisUtterance(section.text);
                    this.utterance.lang = 'id-ID';
                    this.utterance.rate = this.speed;
                    this.utterance.pitch = 1;
                    this.utterance.volume = 1;
                    
                    // Get Indonesian voice if available
                    const voices = speechSynthesis.getVoices();
                    const indonesianVoice = voices.find(voice => 
                        voice.lang.startsWith('id') || voice.lang.startsWith('id-ID')
                    );
                    if (indonesianVoice) {
                        this.utterance.voice = indonesianVoice;
                    }

                    this.utterance.onstart = () => {
                        console.log('Started reading section:', index + 1);
                        
                        // Estimate reading duration based on text length and speed
                        const estimatedDuration = (section.text.length / 15) * 1000 / this.speed;
                        const startTime = Date.now();
                        
                        // Update progress bar smoothly
                        this.progressInterval = setInterval(() => {
                            if (!this.isPlaying) {
                                clearInterval(this.progressInterval);
                                return;
                            }
                            
                            const elapsed = Date.now() - startTime;
                            const sectionProgress = Math.min(elapsed / estimatedDuration, 1);
                            
                            // Calculate total progress
                            const previousSectionsProgress = (this.currentSection / this.sections.length) * 100;
                            const currentSectionProgress = (sectionProgress / this.sections.length) * 100;
                            this.progress = previousSectionsProgress + currentSectionProgress;
                            
                            // Update current time
                            const totalElapsed = this.calculateElapsedTime() + (elapsed / 1000);
                            this.currentTime = this.formatTime(Math.floor(totalElapsed));
                        }, 100);
                    };

                    this.utterance.onend = () => {
                        if (this.progressInterval) {
                            clearInterval(this.progressInterval);
                        }
                        
                        if (!this.isPlaying) return;
                        
                        console.log('Finished section:', this.currentSection + 1, '/', this.sections.length);
                        
                        this.removeHighlight(section.element);
                        this.currentSection++;
                        this.updateProgress();
                        
                        if (this.currentSection < this.sections.length && this.isPlaying) {
                            // Delay yang lebih pendek untuk transisi lebih smooth
                            setTimeout(() => this.readSection(this.currentSection), 200);
                        } else {
                            console.log('Completed all sections');
                            this.stopReading();
                        }
                    };

                    this.utterance.onerror = (event) => {
                        if (this.progressInterval) {
                            clearInterval(this.progressInterval);
                        }
                        
                        if (event.error === 'interrupted') {
                            console.log('Section interrupted (normal)');
                            return;
                        }
                        
                        console.error('Speech synthesis error:', event.error, event);
                        
                        if (event.error === 'network' || event.error === 'synthesis-failed') {
                            console.log('Trying next section...');
                            this.removeHighlight(section.element);
                            this.currentSection++;
                            if (this.currentSection < this.sections.length && this.isPlaying) {
                                setTimeout(() => this.readSection(this.currentSection), 500);
                            } else {
                                this.stopReading();
                            }
                        } else {
                            this.stopReading();
                        }
                    };

                    try {
                        speechSynthesis.speak(this.utterance);
                    } catch (e) {
                        console.error('Error speaking:', e);
                        this.stopReading();
                    }
                },
                pauseReading() {
                    if (speechSynthesis.speaking) {
                        speechSynthesis.pause();
                    }
                    this.isPlaying = false;
                },

                stopReading() {
                    this.forceStopAllSpeech();
                    this.currentTime = '0:00';
                    this.showControls = false;
                },

                nextSection() {
                    if (this.currentSection < this.sections.length - 1) {
                        speechSynthesis.cancel();
                        this.currentSection++;
                        this.updateProgress();
                        if (this.isPlaying) {
                            setTimeout(() => this.readSection(this.currentSection), 200);
                        }
                    }
                },

                previousSection() {
                    if (this.currentSection > 0) {
                        speechSynthesis.cancel();
                        this.currentSection--;
                        this.updateProgress();
                        if (this.isPlaying) {
                            setTimeout(() => this.readSection(this.currentSection), 200);
                        }
                    }
                },

                updateSpeed() {
                    if (this.utterance && this.isPlaying) {
                        const wasPlaying = this.isPlaying;
                        const currentIdx = this.currentSection;
                        
                        speechSynthesis.cancel();
                        
                        if (wasPlaying) {
                            setTimeout(() => {
                                this.currentSection = currentIdx;
                                this.readSection(this.currentSection);
                            }, 200);
                        }
                    }
                },

                updateProgress() {
                    this.progress = ((this.currentSection + 1) / this.sections.length) * 100;
                    
                    const elapsedSections = this.currentSection + 1;
                    const avgCharsPerSection = this.sections.reduce((sum, s) => sum + s.text.length, 0) / this.sections.length;
                    const estimatedSeconds = Math.ceil((elapsedSections * avgCharsPerSection) / 15);
                    this.currentTime = this.formatTime(estimatedSeconds);
                },

                highlightSection(element) {
                    if (element) {
                        element.classList.add('reading-highlight');
                        element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                },

                removeHighlight(element) {
                    if (element) {
                        element.classList.remove('reading-highlight');
                    }
                },

                formatTime(seconds) {
                    const mins = Math.floor(seconds / 60);
                    const secs = seconds % 60;
                    return `${mins}:${secs.toString().padStart(2, '0')}`;
                }
            };
        }
    </script>

</body>

</html>