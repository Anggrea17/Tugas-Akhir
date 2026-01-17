<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">
    <meta charset="UTF-8">
    <title>Kelola User - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen" x-cloak x-data="modalHandler()" x-init="init()">

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
                    class="flex items-center px-4 py-2 text-gray-700 bg-yellow-100 rounded">
                    <svg class="w-5 h-5 mr-3 text-yellow-700" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
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

        <!-- Konten Utama -->
        <div class="max-w-6xl mx-auto mt-8 px-4">
            <div class="flex flex-col md:flex-row justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-700 md:mb-0 mb-4">Kelola Pengguna</h2>
                <div class="flex items-center space-x-2 w-full md:w-auto">
                    <!-- Form pencarian -->
                    <form method="GET" action="{{ route('admin.users') }}"
                        class="flex items-center space-x-2 flex-1">
                        <div class="relative flex-1">
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400"
                                placeholder="Cari user...">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <img src="{{ asset('bahan/searchicon.png') }}" alt="Cari"
                                    class="w-5 h-5 opacity-60">
                            </div>
                        </div>
                        <!-- Tombol filter -->
                        <div class="relative" x-data="{ open: false }">
                            <button type="button" @click="open = !open"
                                class="bg-yellow-500 text-white px-4 py-2 rounded-full hover:bg-yellow-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                                </svg>
                            </button>
                            <!-- Dropdown Menu -->
                            <div x-show="open" x-cloak @click.away="open = false"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95">
                                <a href="{{ route('admin.users', ['search' => request('search')]) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 @if (empty(request('role'))) bg-yellow-200 font-semibold @endif">
                                    Semua Role
                                </a>
                                <a href="{{ route('admin.users', ['search' => request('search'), 'role' => 'user']) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 @if (request('role') === 'user') bg-yellow-200 font-semibold @endif">
                                    User
                                </a>
                                <a href="{{ route('admin.users', ['search' => request('search'), 'role' => 'admin']) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 @if (request('role') === 'admin') bg-yellow-200 font-semibold @endif">
                                    Admin
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Tombol Tambah User -->
                    <button @click="openPilihRoleModal()" class="focus:outline-none" title="Tambah User">
                        <img src="{{ asset('bahan/plusicon.png') }}" alt="Tambah User" class="w-8 h-8">
                    </button>
                </div>
            </div>

            <!-- notifikasi Message user -->
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-500"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-2"
                    class="bg-green-100 text-green-800 p-3 rounded mb-4 shadow">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-500"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-2"
                    class="bg-red-100 text-red-700 p-3 rounded mb-4 shadow">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded shadow-sm">
                    <thead class="bg-yellow-400 text-white">
                        <tr>
                            <th class="text-left py-2 px-4 border-b">No</th>
                            <th class="text-left py-2 px-4 border-b">Nama</th>
                            <th class="text-left py-2 px-4 border-b">Email</th>
                            <th class="text-left py-2 px-4 border-b">Role</th>
                            <th class="text-left py-2 px-4 border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr class="hover:bg-yellow-50">
                                <td class="py-2 px-4 border-b">
                                    {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                <td class="py-2 px-4 border-b">{{ $user->nama }}</td>
                                <td class="py-2 px-4 border-b">{{ $user->email }}</td>
                                <td class="py-2 px-4 border-b capitalize">{{ $user->role }}</td>
                                <td class="py-2 px-4 border-b">
                                    <div class="flex flex-wrap gap-1">
                                        @if (Auth::id() !== $user->id)
                                            <button type="button"
                                                @click="openModal('{{ route('admin.users.delete', $user->id) }}', '{{ addslashes($user->nama) }}')"
                                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                                Hapus
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
                                            Edit
                                        </a>
                                        <button type="button"
                                            @click="openLihatModal({{ json_encode([
                                                'nama' => $user->nama,
                                                'email' => $user->email,
                                                'role' => $user->role,
                                                'alamat' => $user->alamat ?? '-',
                                                'no_hp' => $user->no_hp ?? '-',
                                                'username' => $user->username,
                                                'bayis' => $user->bayis ?? [],
                                            ]) }})"
                                            class="bg-lime-500 text-white px-3 py-1 rounded hover:bg-lime-600 text-sm">
                                            Lihat
                                        </button>
                                        @if ($user->role === 'user')
                                            <a href="{{ route('admin.kelolabayi', $user->id) }}"
                                                class="px-3 py-1 bg-orange-500 text-white rounded hover:bg-orange-600 text-sm">
                                                Kelola Bayi
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada user yang
                                    ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $users->links('pagination::tailwind') }}
            </div>
        </div>

        <!-- Modal Hapus -->
        <div x-show="modalHapus" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto">

            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

            <!-- Modal container -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
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
                                    Hapus Data User
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Apakah Anda yakin ingin menghapus user
                                        <span class="font-medium text-gray-700" x-text="userToDelete?.nama"></span>?
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal buttons -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <form id="hapusForm" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                                Hapus
                            </button>
                        </form>
                        <button type="button" @click="closeAllModals()"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Pilih Role -->
        <div x-show="modalRole" x-cloak x-transition
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            @click.self="closeAllModals()">
            <div class="bg-white p-6 rounded shadow-lg w-80 text-center">
                <h2 class="text-lg font-semibold mb-4 text-gray-700">Pilih Tipe Pengguna</h2>
                <div class="flex flex-col gap-3">
                    <button @click="openForm('orangtua')"
                        class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded">
                        User
                    </button>
                    <button @click="openForm('admin')"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        Admin
                    </button>
                    <button @click="closeAllModals()" class="text-gray-500 hover:underline mt-2">Batal</button>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Orang Tua -->
        <div x-show="modalFormOrangtua" x-cloak x-transition
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow-lg w-full max-w-md overflow-y-auto max-h-[90vh] relative">

                <button @click="closeAllModals()"
                    class="absolute top-4 right-4 text-gray-500 hover:text-red-600 text-2xl font-bold">&times;</button>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-yellow-600">Tambah User</h3>

                </div>

                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <input type="hidden" name="role" value="user">

                    <!-- Data Orang Tua -->
                    <input type="text" name="nama" placeholder="Nama" required
                        class="mb-2 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                        value="{{ old('nama') }}">
                    <input type="text" name="alamat" placeholder="Alamat" required
                        class="mb-2 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                        value="{{ old('alamat') }}">
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                        class="mb-2 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                        maxlength="20" required oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        placeholder="No Hp">
                    <input type="email" name="email" placeholder="Email" required
                        class="mb-2 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                        value="{{ old('email') }}">
                    <input type="text" name="username" placeholder="Username" required
                        class="mb-2 w-full border rounded px-3 py-2  focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                        value="{{ old('username') }}">
                    <input type="password" name="password" placeholder="Password" required
                        class="mb-2 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500">
                    <div class="flex justify-end gap-2 mt-2">
                        <button type="button" @click="closeAllModals()"
                            class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
                        <button type="submit" class="bg-yellow-400 text-white px-4 py-2 rounded hover:bg-yellow-500">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Tambah Admin -->
        <div x-show="modalFormAdmin" x-cloak x-transition
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow-lg w-full max-w-md overflow-y-auto max-h-[90vh] relative">

                <button @click="closeAllModals()"
                    class="absolute top-4 right-4 text-gray-500 hover:text-red-600 text-2xl font-bold">&times;</button>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-yellow-600">Tambah Admin</h2>

                </div>
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <input type="hidden" name="role" value="admin">
                    <input type="text" name="nama" placeholder="Nama" required
                        class="mb-2 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                        value="{{ old('nama') }}" placeholder="Nama">

                    <input type="text" name="username" placeholder="Username" required
                        class="mb-2 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                        value="{{ old('username') }}">
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                        class="mb-2 w-full border rounded px-3 py-2 focus:outline-none
                        focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                        maxlength="20" required oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        placeholder="No Hp">
                    <input type="text" name="alamat" placeholder="Alamat" required
                        class="mb-2 w-full border rounded px-3 py-2 focus:outline-none
                        focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                        value="{{ old('alamat') }}">
                    <input type="email" name="email" placeholder="Email" required
                        class="mb-2 w-full border rounded px-3 py-2 focus:outline-none
                        focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                        value="{{ old('email') }}">
                    <input type="password" name="password" placeholder="Password" required
                        class="mb-2 w-full border rounded px-3 py-2 focus:outline-none
                        focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500">
                    <div class="flex justify-end gap-2 mt-2">
                        <button type="button" @click="closeAllModals()"
                            class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
                        <button type="submit"
                            class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal preview User -->
        <div x-show="modalLihat" x-cloak class="fixed inset-0 flex items-center justify-center bg-black/60 z-50"
            @click.self="modalLihat = false" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90">

            <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden relative shadow-2xl">
                <!-- Tombol Close -->
                <button @click="modalLihat = false"
                    class="absolute top-4 right-4 z-10 text-gray-500 hover:text-red-600 text-2xl font-bold">
                    &times;
                </button>

                <div class="overflow-y-auto max-h-[90vh]">
                    <!-- Header -->
                    <div class="relative p-8 border-b">
                        <h2 class="text-3xl font-bold text-gray-800 text-center"
                            x-text="lihatData.nama || 'Detail User'"></h2>
                        <p class="text-center text-gray-500 mt-1">Informasi Pengguna</p>
                    </div>

                    <!-- Content -->
                    <div class="p-8 space-y-6">
                        <!-- Info Cards Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 p-4 rounded-xl text-center border border-blue-200">
                                <p class="text-xs text-blue-600 font-semibold mb-1">Username</p>
                                <p class="text-lg font-bold text-blue-900 truncate"
                                    x-text="lihatData.username || '-'"></p>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-xl text-center border border-purple-200">
                                <p class="text-xs text-purple-600 font-semibold mb-1">Role</p>
                                <p class="text-lg font-bold text-purple-900" x-text="lihatData.role || '-'"></p>
                            </div>
                            <div
                                class="bg-green-50 p-4 rounded-xl text-center border border-green-200 col-span-2 md:col-span-1">
                                <p class="text-xs text-green-600 font-semibold mb-1">No HP</p>
                                <p class="text-lg font-bold text-green-900" x-text="lihatData.no_hp || '-'"></p>
                            </div>
                        </div>

                        <!-- Informasi Kontak -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-200">
                            <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                    </path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                Informasi Kontak
                            </h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-gray-600 text-sm">Email:</span>
                                    <p class="font-semibold text-gray-800 break-all" x-text="lihatData.email || '-'">
                                    </p>
                                </div>
                                <div>
                                    <span class="text-gray-600 text-sm">Alamat:</span>
                                    <p class="font-semibold text-gray-800" x-text="lihatData.alamat || '-'"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Data Bayi -->
                        <div class="bg-gradient-to-r from-pink-50 to-purple-50 p-6 rounded-xl border border-pink-200">
                            <h3 class="text-lg font-bold text-pink-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Data Bayi
                                <span class="ml-2 text-sm font-normal text-pink-600"
                                    x-show="lihatData.bayis && lihatData.bayis.length > 0">
                                    (<span x-text="lihatData.bayis.length"></span> bayi)
                                </span>
                            </h3>

                            <template x-if="lihatData.bayis && lihatData.bayis.length > 0">
                                <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                    <template x-for="(bayi, index) in lihatData.bayis"
                                        :key="bayi.id || bayi.nama_bayi">
                                        <div class="bg-white p-5 rounded-xl shadow-sm border-2 hover:shadow-md transition-all"
                                            :class="[
                                                'border-yellow-300',
                                                'border-pink-300',
                                                'border-blue-300',
                                                'border-green-300'
                                            ][index % 4]">

                                            <!-- Nama Bayi Header -->
                                            <div class="flex items-center mb-3 pb-2 border-b">
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold mr-3"
                                                    :class="[
                                                        'bg-yellow-400',
                                                        'bg-pink-400',
                                                        'bg-blue-400',
                                                        'bg-green-400'
                                                    ][index % 4]">
                                                    <span
                                                        x-text="(bayi.nama_bayi || '?').charAt(0).toUpperCase()"></span>
                                                </div>
                                                <h4 class="font-bold text-lg text-gray-800"
                                                    x-text="bayi.nama_bayi || '-'"></h4>
                                            </div>

                                            <!-- Info Grid -->
                                            <div class="grid grid-cols-2 gap-3 text-sm">
                                                <div>
                                                    <p class="text-gray-500 text-xs">Jenis Kelamin</p>
                                                    <p class="font-semibold text-gray-800"
                                                        x-text="bayi.jenis_kelamin || '-'"></p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-500 text-xs">Tanggal Lahir</p>
                                                    <p class="font-semibold text-gray-800"
                                                        x-text="bayi.tanggal_lahir ? new Date(bayi.tanggal_lahir).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-'">
                                                    </p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-500 text-xs">Berat</p>
                                                    <p class="font-semibold text-gray-800">
                                                        <span
                                                            x-text="bayi.berat > 0 ? bayi.berat + ' kg' : '-'"></span>
                                                    </p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-500 text-xs">Tinggi</p>
                                                    <p class="font-semibold text-gray-800">
                                                        <span
                                                            x-text="bayi.tinggi > 0 ? bayi.tinggi + ' cm' : '-'"></span>
                                                    </p>
                                                </div>
                                                <div class="col-span-2">
                                                    <p class="text-gray-500 text-xs">Jenis Susu</p>
                                                    <p class="font-semibold text-gray-800"
                                                        x-text="bayi.jenis_susu || '-'"></p>
                                                </div>
                                            </div>

                                            <!-- Info Susu Formula -->
                                            <template x-if="bayi.jenis_susu === 'Sufor' || bayi.jenis_susu === 'Mix'">
                                                <div class="mt-3 pt-3 border-t grid grid-cols-2 gap-3 text-sm">
                                                    <div>
                                                        <p class="text-gray-500 text-xs">Kalori per Porsi</p>
                                                        <p class="font-semibold text-orange-600">
                                                            <span
                                                                x-text="bayi.kalori_per_porsi > 0 ? bayi.kalori_per_porsi + ' kcal' : '-'"></span>
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <p class="text-gray-500 text-xs">Porsi per Hari</p>
                                                        <p class="font-semibold text-orange-600">
                                                            <span
                                                                x-text="bayi.jumlah_porsi_per_hari > 0 ? bayi.jumlah_porsi_per_hari : '-'"></span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </template>

                                            <!-- Info ASI -->
                                            <template x-if="bayi.jenis_susu === 'ASI' || bayi.jenis_susu === 'Mix'">
                                                <div class="mt-3 pt-3 border-t">
                                                    <p class="text-gray-500 text-xs">Volume ASI</p>
                                                    <p class="font-semibold text-green-600">
                                                        <span
                                                            x-text="bayi.volume_asi > 0 ? bayi.volume_asi + ' ml' : '-'"></span>
                                                    </p>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <template x-if="!lihatData.bayis || lihatData.bayis.length === 0">
                                <div class="text-center py-8">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-2" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-gray-500 italic">Belum ada data bayi</p>
                                </div>
                            </template>
                        </div>

                        <!-- Tombol Tutup -->
                        <div class="flex justify-end pt-4">
                            <button @click="closeAllModals()"
                                class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 font-semibold transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- JavaScript -->
        <script>
            function modalHandler() {
                return {
                    sidebarOpen: false,
                    modalHapus: false,
                    modalRole: false,
                    modalFormAdmin: false,
                    modalFormOrangtua: false,
                    modalLihat: false,
                    hapusUrl: '',
                    lihatData: {},
                    userToDelete: null,

                    bayis: [{
                        nama_bayi: '',
                        tanggal_lahir: '',
                        jenis_kelamin: '',
                        berat: '',
                        tinggi: '',
                        jenis_susu: '',
                        volume_asi: '',
                        kalori_per_porsi: '',
                        jumlah_porsi_per_hari: ''
                    }],

                    addBayi() {
                        this.bayis.push({
                            nama_bayi: '',
                            tanggal_lahir: '',
                            jenis_kelamin: '',
                            berat: '',
                            tinggi: '',
                            jenis_susu: '',
                            volume_asi: '',
                            kalori_per_porsi: '',
                            jumlah_porsi_per_hari: ''
                        });
                    },

                    removeBayi(index) {
                        if (this.bayis.length > 1) {
                            this.bayis.splice(index, 1);
                        }
                    },

                    init() {
                        const form = document.getElementById('hapusForm');
                        this.$watch('hapusUrl', value => {
                            if (form) form.action = value;
                        });

                        // Auto-open modal setelah notifikasi error hilang (6 detik)
                        @if ($errors->any())
                            setTimeout(() => {
                                const oldRole = '{{ old('role') }}';
                                if (oldRole === 'admin') {
                                    this.modalFormAdmin = true;
                                } else if (oldRole === 'user') {
                                    this.modalFormOrangtua = true;
                                }
                            }, 6000); // 6 detik, sesuai dengan waktu notifikasi hilang
                        @endif
                    },

                    openModal(url, userName) {
                        this.hapusUrl = url;
                        this.userToDelete = {
                            nama: userName
                        };
                        this.modalHapus = true;
                    },

                    openPilihRoleModal() {
                        this.modalRole = true;
                    },

                    openForm(role) {
                        this.closeAllModals();
                        if (role === 'admin') {
                            this.modalFormAdmin = true;
                        } else if (role === 'orangtua') {
                            this.modalFormOrangtua = true;
                        }
                    },

                    openLihatModal(data) {
                        if (!data.bayis) data.bayis = [];
                        this.lihatData = data;
                        this.modalLihat = true;
                    },

                    closeAllModals() {
                        this.modalRole = false;
                        this.modalFormAdmin = false;
                        this.modalFormOrangtua = false;
                        this.modalHapus = false;
                        this.modalLihat = false;

                        this.lihatData = {};
                        this.hapusUrl = '';
                        this.userToDelete = null;

                        this.bayis = [{
                            nama_bayi: '',
                            tanggal_lahir: '',
                            jenis_kelamin: '',
                            berat: '',
                            tinggi: '',
                            jenis_susu: '',
                            volume_asi: '',
                            kalori_per_porsi: '',
                            jumlah_porsi_per_hari: ''
                        }];
                    }
                }
            }
        </script>
</body>

</html>
