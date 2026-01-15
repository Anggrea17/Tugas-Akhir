<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    <!-- Notifikasi Success -->
    @if (session('success'))
    <div id="success-notification" 
         class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 z-50 animate-slide-in">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span>{{ session('success') }}</span>
        <button onclick="closeNotification('success-notification')" class="ml-4 hover:bg-green-600 rounded p-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    @endif

    <!-- Notifikasi Error -->
    @if (session('error'))
    <div id="error-notification" 
         class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 z-50 animate-slide-in">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <span>{{ session('error') }}</span>
        <button onclick="closeNotification('error-notification')" class="ml-4 hover:bg-red-600 rounded p-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    @endif

    <!-- Notifikasi Status (Laravel Default) -->
    @if (session('status'))
    <div id="status-notification" 
         class="fixed top-4 right-4 bg-blue-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 z-50 animate-slide-in">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ session('status') }}</span>
        <button onclick="closeNotification('status-notification')" class="ml-4 hover:bg-blue-600 rounded p-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    @endif

    <div class="flex flex-col md:flex-row bg-white rounded-lg shadow-xl w-full max-w-4xl overflow-hidden">

        <!-- Gambar Kiri -->
        <div class="md:w-1/2 bg-gradient-to-br from-pink-100 via-orange-50 to-yellow-100 
            flex items-center justify-center h-64 md:h-auto 
            rounded-r-2xl shadow-inner animate-gradient-dreamy">
            <img src="{{ asset('bahan/baby log.png') }}" alt="Login Visual" class="w-full h-full object-cover">
        </div>
        
        <!-- Form Login -->
        <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-8">Login User</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Username -->
                <div class="mb-5">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" id="username" name="login" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 transition"
                        placeholder="Masukkan username" value="{{ old('username') }}">
                    @error('login')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password + Toggle -->
                <div class="mb-6 relative">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 transition"
                        placeholder="Masukkan password">

                    <!-- Tombol Toggle Mata -->
                    <button type="button" id="password-toggle"
                        class="absolute right-3 top-9 text-gray-500 hover:text-gray-700 focus:outline-none">
                        <!-- Ikon default (mata terbuka) -->
                        <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
                        </svg>
                        <!-- Ikon mata tertutup (hidden di awal) -->
                        <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.057 10.057 0 012.112-3.592M6.219 6.219A9.957 9.957 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.055 10.055 0 01-4.157 5.09M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                        </svg>
                    </button>
                    <!-- Lupa Password -->
                    <div class="text-right mb-4">
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">Lupa password?</a>
                    </div>
                </div>

                <!-- Tombol Login -->
                <button type="submit"
                    class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition hover:-translate-y-0.5 mb-4">
                    Login
                </button>
            </form>

            <!-- Separator -->
            <div class="flex items-center gap-4 my-6 text-sm text-gray-500">
                <div class="flex-1 h-px bg-gray-300"></div>
                <span class="text-gray-500">OR</span>
                <div class="flex-1 h-px bg-gray-300"></div>
            </div>

            <!-- Google Login -->
            <a href="{{ route('google.login') }}"
                class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition flex items-center justify-center gap-2 mb-6">
                <svg class="w-5 h-5" viewBox="0 0 48 48">
                    <path fill="#EA4335"
                        d="M24 9.5c3.54 0 6.24 1.53 7.68 2.82l5.66-5.67C33.66 3.13 29.16 1 24 1 14.74 1 6.86 6.83 3.64 14.27l6.97 5.42C12.18 13.3 17.62 9.5 24 9.5z" />
                    <path fill="#4285F4"
                        d="M46.5 24.5c0-1.57-.14-3.08-.39-4.5H24v8.52h12.72c-.55 3.14-2.31 5.81-4.94 7.62l7.66 5.95c4.49-4.14 7.06-10.24 7.06-17.59z" />
                    <path fill="#FBBC05"
                        d="M10.61 28.95A14.46 14.46 0 019.5 24c0-1.72.31-3.38.85-4.95l-6.97-5.42A23.93 23.93 0 000 24c0 3.91.94 7.61 2.6 10.89l7.46-5.94z" />
                    <path fill="#34A853"
                        d="M24 48c6.16 0 11.31-2.03 15.07-5.52l-7.66-5.95c-2.09 1.4-4.78 2.22-7.41 2.22-6.38 0-11.82-3.8-13.39-9.19l-7.46 5.94C6.86 41.17 14.74 48 24 48z" />
                </svg>
                <span>Login dengan Google</span>
            </a>

            <!-- Register -->
            <p class="text-center text-gray-600 text-sm">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-semibold">Daftar di sini</a>
            </p>
        </div>
    </div>

    <!-- Script Toggle Mata -->
    <script>
        const passwordInput = document.getElementById('password');
        const toggleButton = document.getElementById('password-toggle');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');

        if (passwordInput && toggleButton && eyeOpen && eyeClosed) {
            const setIcons = () => {
                const isHidden = passwordInput.type === 'password';
                if (isHidden) {
                    eyeOpen.classList.add('hidden');
                    eyeClosed.classList.remove('hidden');
                } else {
                    eyeOpen.classList.remove('hidden');
                    eyeClosed.classList.add('hidden');
                }

                const isVisible = !isHidden;
                toggleButton.setAttribute('aria-pressed', String(isVisible));
                toggleButton.setAttribute('aria-label', isVisible ? 'Sembunyikan password' : 'Tampilkan password');
            };

            setIcons();

            toggleButton.addEventListener('click', (e) => {
                e.preventDefault();
                passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
                setIcons();
            });
        }

        // Auto-hide notifications setelah 5 detik
        function autoHideNotifications() {
            const notifications = [
                'success-notification',
                'error-notification', 
                'status-notification'
            ];

            notifications.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    setTimeout(() => {
                        element.classList.add('animate-slide-out');
                        setTimeout(() => {
                            element.remove();
                        }, 300);
                    }, 5000);
                }
            });
        }

        // Fungsi close manual
        function closeNotification(id) {
            const element = document.getElementById(id);
            if (element) {
                element.classList.add('animate-slide-out');
                setTimeout(() => {
                    element.remove();
                }, 300);
            }
        }

        // Jalankan auto-hide saat halaman load
        document.addEventListener('DOMContentLoaded', autoHideNotifications);
    </script>

    <!-- Custom Animations -->
    <style>
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }

        .animate-slide-out {
            animation: slideOut 0.3s ease-in;
        }
    </style>

</body>

</html>