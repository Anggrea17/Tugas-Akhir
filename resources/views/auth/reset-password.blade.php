<!DOCTYPE html>
<html>

<head>
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">

    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <form method="POST" action="{{ route('password.update') }}" class="bg-white p-6 rounded shadow-md w-full max-w-md">
        @csrf
        <!-- Token dari link reset -->
        <input type="hidden" name="token" value="{{ request()->route('token') }}">

        <h2 class="text-2xl font-bold mb-4 text-center text-gray-700">Reset Password</h2>

        <!-- Email otomatis dari request -->
        <label class="block mb-2 text-sm font-semibold">Email</label>
        <input type="email" name="email" value="{{ request()->email }}" readonly
            class="w-full p-2 border rounded mb-4 bg-gray-100 cursor-not-allowed">

        <!-- Password Baru -->
        <label class="block mb-2 text-sm font-semibold">Password Baru</label>
        <input type="password" name="password" class="w-full p-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition" required>

        <!-- Konfirmasi Password -->
        <label class="block mb-2 text-sm font-semibold">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="w-full p-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition" required>

        <!-- Error Message -->
        @error('email')
            <p class="text-red-500 text-sm mb-4">{{ $message }}</p>
        @enderror
        @error('password')
            <p class="text-red-500 text-sm mb-4">{{ $message }}</p>
        @enderror

        <!-- Submit -->
        <button type="submit" class="w-full bg-yellow-600 text-white py-2 rounded hover:bg-yellow-700 transition">
            Ubah Password
        </button>
    </form>
</body>

</html>
