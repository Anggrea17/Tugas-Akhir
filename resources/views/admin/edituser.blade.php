<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen" x-data="{ sidebarOpen: false }">

    <!-- Sidebar -->
    <aside
        class="fixed z-30 inset-y-0 left-0 w-64 bg-white shadow-md h-screen transform md:translate-x-0 transition-transform duration-200 ease-in-out"
        :class="{ '-translate-x-full': !sidebarOpen }">
        <div class="p-6">
            <img src="{{ asset('bahan/lognav.png') }}" alt="Logo GEMAS" />
            <nav class="space-y-2 mt-6">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-yellow-100 rounded">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-yellow-700' : 'text-gray-400' }}"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.users') }}"
                    class="flex items-center px-4 py-2 text-gray-700 bg-yellow-100 rounded">
                    <svg class="w-5 h-5 mr-3 text-yellow-700"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path
                            d="M5.121 17.804A4 4 0 017.757 16h8.486a4 4 0 012.636 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z 125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z">
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
                    Kelola Kategori Mpasi
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
            <div class="relative ml-auto" x-data="{ open: false }">
                <button @click="open = !open" class="focus:outline-none">
                    <img src="{{ asset('bahan/admin-icon.png') }}" alt="Admin"
                        class="w-10 h-10 rounded-full border-2 border-white shadow">
                </button>

                <div x-show="open" x-clock @click.away="open = false"
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

        <!-- Content Area -->
        <div class="p-8">
            <div class="bg-white rounded-lg shadow-lg p-8 max-w-4xl mx-auto">
                <h1 class="text-2xl font-bold mb-6 text-yellow-600">Edit Data User</h1>

                <!-- Notifikasi Error Global -->
                @if ($errors->any())
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-cloak
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-500"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-2"
                        class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-6 shadow">
                        <ul class="list-disc ml-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Notifikasi Success -->
                @if (session('success'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-cloak
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-500"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-2"
                        class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-6 shadow">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Grid Layout for Form Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block font-semibold mb-2 text-gray-700">Nama</label>
                            <input type="text" name="nama" value="{{ old('nama', $user->nama) }}"
                                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                                required>
                        </div>

                        <div>
                            <label class="block font-semibold mb-2 text-gray-700">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                                required>
                        </div>

                        <div>
                            <label class="block font-semibold mb-2 text-gray-700">Username</label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}"
                                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                                required>
                        </div>

                        <div>
                            <label class="block font-semibold mb-2 text-gray-700">No HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                                maxlength="20" required oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                placeholder="No Hp">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block font-semibold mb-2 text-gray-700">Alamat</label>
                            <input type="text" name="alamat" value="{{ old('alamat', $user->alamat) }}"
                                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                                required>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.users') }}"
                            class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-400 font-semibold">
                            Batal
                        </a>
                        <button type="submit" class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600 font-semibold">
                            Simpan
                        </button>
                    </div>
                </form>

                <!-- Form Ganti Password -->
                <form action="{{ route('admin.users.updatePassword', $user->id) }}" method="POST" class="mt-8">
                    @csrf
                    @method('PUT')
                    <hr class="my-6 border-gray-300">
                    <h2 class="text-xl font-semibold text-yellow-600 mb-4">
                        Ganti Password (Opsional)
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block font-semibold mb-2 text-gray-700">Password Baru</label>
                            <input type="password" name="password"
                                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                                required>
                        </div>

                        <div>
                            <label class="block font-semibold mb-2 text-gray-700">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation"
                                class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                                required>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 font-semibold">
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>