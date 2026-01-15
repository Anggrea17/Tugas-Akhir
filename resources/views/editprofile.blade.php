<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

</head>

<body class="bg-gray-50 min-h-screen">
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
            <li>
                @auth
                    <a href="{{ route('perkembangan.index') }}"
                        class="text-black-600 font-semibold hover:text-yellow-600 transition-colors duration-200">
                        Perkembangan Bayi
                    </a>
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

    <div class="max-w-5xl mx-auto py-10 px-4" x-data="profileForm()" x-init="tab = '{{ session('tab', 'user') }}'">

        <!-- Header -->
        <header class="mb-10 text-center">
            <h1 class="text-3xl font-bold text-yellow-800">Edit Profil</h1>
            <p class="text-gray-600 mt-2">Kelola informasi profil dan data bayi Anda</p>
        </header>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow-sm mb-8">
            <nav class="flex border-b border-gray-200 text-sm font-medium">
                <button @click="tab = 'user'"
                    :class="tab === 'user' ? 'border-b-2 border-yellow-500 text-yellow-600 bg-yellow-50' :
                        'text-gray-600 hover:text-gray-800'"
                    class="flex-1 py-3 px-6 transition-colors">Data User</button>

                <button @click="tab = 'bayi'"
                    :class="tab === 'bayi' ? 'border-b-2 border-yellow-500 text-yellow-600 bg-yellow-50' :
                        'text-gray-600 hover:text-gray-800'"
                    class="flex-1 py-3 px-6 transition-colors">Data Bayi</button>

                <button @click="tab = 'password'"
                    :class="tab === 'password' ? 'border-b-2 border-yellow-500 text-yellow-600 bg-yellow-50' :
                        'text-gray-600 hover:text-gray-800'"
                    class="flex-1 py-3 px-6 transition-colors">Ubah Password</button>
            </nav>
        </div>
        <!-- End Tabs -->

        <!-- Section User -->
        <section x-show="tab === 'user'" x-transition>
            {{-- Notif error untuk tab user --}}
            @if ($errors->user->any() && session('tab') === 'user')
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
                    class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                    <ul>
                        @foreach ($errors->user->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Notif sukses --}}
            @if (session('success_user') && session('tab') === 'user')
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
                    class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                    {{ session('success_user') }}
                </div>
            @endif
            <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" action="{{ route('profile.updateUser') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="tab" value="user">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama', $user->nama) }}"
                                class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Username</label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}"
                                class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                class="mt-1 w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-500 cursor-not-allowed">
                            <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat</label>
                            <input type="text" name="alamat" value="{{ old('alamat', $user->alamat) }}"
                                class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">No HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                                class="mt-1 w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'');"">
                        </div>
                    </div>
                    <div class="flex justify-end mt-6 border-t pt-4">
                        <button type="submit"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg shadow">Simpan</button>
                    </div>
                </form>
            </div>
            <!-- End Form User -->
        </section>

        <!-- Section Bayi -->
        <section x-show="tab === 'bayi'" x-transition>
            {{-- ✅ Notif sukses update bayi --}}
            @if (session('success_bayi') && session('tab') === 'bayi')
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success_bayi') }}
                </div>
            @endif
            {{-- notif sukses delete bayi --}}
            @if (session('success_delete_bayi') && session('tab') === 'bayi')
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success_delete_bayi') }}
                </div>
            @endif
            {{-- ✅ Notif error bayi --}}
            @if ($errors->getBag('bayi')->any())
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul>
                        @foreach ($errors->getBag('bayi')->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Data Bayi</h2>
                    <!-- Tombol Tambah Bayi di luar form -->
                    <div class="mb-4">
                        <button type="button" @click="addBaby()"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">Tambah
                            Bayi</button>
                    </div>

                </div>

                <form method="POST" action="{{ route('profile.updateBayi') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <template x-for="(baby, index) in babies" :key="index">

                        <div class="border rounded-lg p-4 bg-gray-50">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="font-medium">Bayi <span x-text="index+1"></span></h3>
                                <div>
                                    <!-- Untuk bayi yang sudah ada di database -->
                                    <button type="button" x-show="baby.id"
                                        @click="showDeleteModal = true; deleteIndex = index; deleteBabyId = baby.id; deleteBabyName = baby.nama_bayi"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                        Hapus
                                    </button>

                                    <!-- Untuk bayi yang baru ditambahkan (belum ada id) -->
                                    <button type="button" x-show="!baby.id" @click="removeBaby(index)"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                        Hapus
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="hidden" :name="`bayi[${index}][id]`" x-model="baby.id">
                                <div>
                                    <label class="block text-sm">Nama Bayi</label>
                                    <input type="text" :name="`bayi[${index}][nama_bayi]`" x-model="baby.nama_bayi"
                                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                </div>
                                <div>
                                    <label class="block text-sm">Tanggal Lahir</label>
                                    <input type="date" :name="`bayi[${index}][tanggal_lahir]`"
                                        x-model="baby.tanggal_lahir"
                                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                </div>
                                <div>
                                    <label class="block text-sm">Jenis Kelamin</label>
                                    <select :name="`bayi[${index}][jenis_kelamin]`" x-model="baby.jenis_kelamin"
                                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm">Berat (kg)</label>
                                    <input type="number" step="0.1" min="2" max="15" :name="`bayi[${index}][berat]`"
                                        x-model="baby.berat"
                                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                </div>
                                <div>
                                    <label class="block text-sm">Tinggi (cm)</label>
                                    <input type="number" step="0.1" min="40" max="100" :name="`bayi[${index}][tinggi]`"
                                        x-model="baby.tinggi"
                                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                </div>
                                <!-- Jenis Susu -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm">Jenis Susu</label>
                                    <select :name="`bayi[${index}][jenis_susu]`" x-model="baby.jenis_susu"
                                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                        <option value="">Pilih Jenis Susu</option>
                                        <option value="ASI">ASI</option>
                                        <option value="Sufor">Susu Formula</option>
                                        <option value="Mix">Campuran (ASI + Sufor)</option>
                                    </select>
                                </div>

                                <!-- Volume ASI -->
                                <div class="md:col-span-2 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                                    x-show="baby.jenis_susu === 'ASI' || baby.jenis_susu === 'Mix'">
                                    <label class="block text-sm">Volume ASI (ml per hari)</label>
                                    <input type="number" step="1" min="200" max="1000" :name="`bayi[${index}][volume_asi]`"
                                        x-model="baby.volume_asi" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                </div>

                                <!-- Kalori per porsi -->
                                <div class="md:col-span-2 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                                    x-show="baby.jenis_susu === 'Sufor' || baby.jenis_susu === 'Mix'">
                                    <label class="block text-sm">Kalori Sufor per Porsi (kkal)</label>
                                    <input type="number" step="1" min="20" max="120" :name="`bayi[${index}][kalori_per_porsi]`"
                                        x-model="baby.kalori_per_porsi" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                </div>

                                <!-- Jumlah porsi -->
                                <div class="md:col-span-2"
                                    x-show="baby.jenis_susu === 'Sufor' || baby.jenis_susu === 'Mix'">
                                    <label class="block text-sm">Jumlah Porsi per Hari</label>
                                    <input type="number" step="1" min="1" max="8"
                                        :name="`bayi[${index}][jumlah_porsi_per_hari]`"
                                        x-model="baby.jumlah_porsi_per_hari"
                                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="babies.length === 0" class="text-center py-6 text-gray-500">
                        Belum ada data bayi, Tambahkan data bayi pertama Anda.
                    </div>

                    <div class="flex justify-end border-t pt-4" x-show="babies.length > 0">
                        <button type="submit"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg shadow">Simpan
                            Semua</button>
                    </div>
                </form>
            </div>
        </section>
        <!-- Section Password -->
        <section x-show="tab === 'password'" x-transition>
            {{-- ✅ Notif sukses update password --}}
            @if (session('success_password'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success_password') }}
                </div>
            @endif

            {{-- ✅ Notif error password --}}
            @if ($errors->getBag('default')->any() && session('tab') === 'password')
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Ubah Password</h2>
                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="tab" value="password">

                    <div class="mb-4">
                        <label class="block text-gray-700">Password Baru</label>
                        <input type="password" name="password"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    </div>

                    <button type="submit"
                        class="bg-yellow-600 text-white px-4 py-2 rounded-lg shadow hover:bg-yellow-700">
                        Ganti Password
                    </button>
                </form>
            </div>
        </section>

        <!-- Custom Delete Confirmation Modal -->
        <div x-show="showDeleteModal" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto">

            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

            <!-- Modal container -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300"
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
                                        Apakah Anda yakin ingin menghapus data bayi
                                        <span class="font-medium text-gray-700" x-text="deleteBabyName"></span>?
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal buttons -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" @click="confirmDelete()"
                            class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                            Hapus
                        </button>
                        <button type="button" @click="showDeleteModal = false"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden form for deleting baby -->
        <form x-ref="deleteForm" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

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

    <script>
        function profileForm() {
            return {
                tab: '{{ old('tab', 'user') }}', // biar tetap stay di tab yg error
                babies: @json($oldBayis), // ✅ kalau ada old() pakai itu dulu, kalau tidak ada pakai data DB
                showDeleteModal: false,
                showDeleteModal: false,
                deleteIndex: null,
                deleteBabyId: null,
                deleteBabyName: '',
                addBaby() {
                    this.babies.push({
                        id: null,
                        nama_bayi: '',
                        tanggal_lahir: '',
                        berat: '',
                        jenis_kelamin: '',
                        jenis_susu: '',
                        tinggi: '',
                        volume_asi: '',
                        kalori_per_porsi: '',
                        jumlah_porsi_per_hari: ''
                    });
                },

                removeBaby(index) {
                    this.babies.splice(index, 1);
                },

                confirmDelete() {
                    if (this.deleteBabyId) {
                        // Hapus bayi yang sudah ada di database
                        const form = this.$refs.deleteForm;
                        form.action = `/profile/bayi/${this.deleteBabyId}`;
                        form.submit();
                    } else {
                        // Hapus bayi yang baru ditambahkan
                        this.removeBaby(this.deleteIndex);
                        this.showDeleteModal = false;
                    }
                }

            }
        }
    </script>
</body>

</html>
