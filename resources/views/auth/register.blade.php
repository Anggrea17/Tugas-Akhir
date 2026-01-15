<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">
    <meta charset="UTF-8">
    <title>Daftar Akun - GEMAS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        /* Nonaktifkan toggle password bawaan browser */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }
        
        input[type="password"]::-webkit-credentials-auto-fill-button,
        input[type="password"]::-webkit-password-toggle-button {
            display: none !important;
        }
    </style>
</head>

<body class="bg-yellow-50">

    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white shadow-lg rounded-xl w-full max-w-2xl p-8">
            <h2 class="text-2xl font-bold text-yellow-700 text-center mb-6">Daftar Akun GEMAS</h2>

            @if ($errors->any())
                <div id="alert-box"
                    class="fixed top-4 right-4 bg-red-50 border border-red-400 text-red-800 p-5 rounded-lg shadow-xl z-50 max-w-2xl w-full mx-4 sm:w-auto sm:mx-0
                    transition-all duration-500 ease-out transform"
                    style="animation: slideInDown 0.5s ease-out;">
                    <div class="flex items-start gap-3">
                        <div class="flex-1">
                            @foreach ($errors->all() as $error)
                                <p class="text-base font-semibold leading-relaxed">{{ $error }}</p>
                            @endforeach
                        </div>
                        <button onclick="document.getElementById('alert-box').remove()" 
                            class="text-red-600 hover:text-red-800 focus:outline-none flex-shrink-0 ml-2 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <form action="{{ url('/register') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}"  required class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <input type="text" name="alamat" value="{{ old('alamat') }}" required class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" required class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
                </div>

      <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="w-full border border-gray-300 p-2 pr-10 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        <button type="button" id="password-toggle"
                            class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700 focus:outline-none">
                            <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.057 10.057 0 012.112-3.592M6.219 6.219A9.957 9.957 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.055 10.055 0 01-4.157 5.09M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full border border-gray-300 p-2 pr-10 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        <button type="button" id="password-confirmation-toggle"
                            class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700 focus:outline-none">
                            <svg id="eye-open-confirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eye-closed-confirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.057 10.057 0 012.112-3.592M6.219 6.219A9.957 9.957 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.055 10.055 0 01-4.157 5.09M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="md:col-span-2 pt-4">
                    <button type="submit"
                        class="w-full bg-yellow-600 text-white py-2 rounded-md hover:bg-yellow-700">
                        Daftar Sekarang
                    </button>
                </div>

                <div class="md:col-span-2 text-center text-sm text-gray-500">
                    Sudah punya akun? <a href="/login" class="text-yellow-700 font-semibold hover:underline">Login di
                        sini</a>
                </div>

            </form>
        </div>
    </div>

    <!-- Script Toggle Mata -->
    <script>
        function setupToggle(inputId, btnId, eyeOpenId, eyeClosedId) {
            const input = document.getElementById(inputId);
            const btn = document.getElementById(btnId);
            const eyeOpen = document.getElementById(eyeOpenId);
            const eyeClosed = document.getElementById(eyeClosedId);

            const setIcons = () => {
                const isHidden = input.type === 'password';
                if (isHidden) {
                    eyeOpen.classList.add('hidden');
                    eyeClosed.classList.remove('hidden');
                } else {
                    eyeOpen.classList.remove('hidden');
                    eyeClosed.classList.add('hidden');
                }
            };

            setIcons();
            btn.addEventListener('click', () => {
                input.type = input.type === 'password' ? 'text' : 'password';
                setIcons();
            });
        }

        // Setup untuk dua input password
        setupToggle('password', 'password-toggle', 'eye-open', 'eye-closed');
        setupToggle('password_confirmation', 'password-confirmation-toggle', 'eye-open-confirm', 'eye-closed-confirm');
    </script>
    
    <!-- Script notif Alert biar auto hilang 8 detik -->
    <script>
        const alertBox = document.getElementById('alert-box');
        if (alertBox ) {
            // Hapus alert setelah 8 detik
            setTimeout(() => {
                alertBox.classList.add('opacity-0');
                alertBox.style.transform = 'translateY(-100%)';
                setTimeout(() => alertBox.remove(), 500);
            }, 8000);
        }
    </script>

</body>

</html>