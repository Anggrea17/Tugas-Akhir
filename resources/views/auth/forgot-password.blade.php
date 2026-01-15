<!DOCTYPE html>
<html>

<head>
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=3">

    <title>Ubah Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta charset="UTF-8">
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <form method="POST" action="{{ route('password.email') }}" class="bg-white p-6 rounded shadow-md w-full max-w-md">
        @csrf
        <h2 class="text-2xl font-bold mb-4 text-center">Ubah Password</h2>

        @if (session('status'))
            <div class="text-green-600 mb-4">{{ session('status') }}</div>
        @endif

        <label class="block mb-2 text-sm font-semibold">Email</label>
        <input type="email" name="email" class="w-full p-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition" required autofocus>

        @error('email')
            <p class="text-red-500 text-sm mb-4">{{ $message }}</p>
        @enderror

        <button type="submit" class="w-full bg-yellow-500 text-white py-2 rounded hover:bg-yellow-700">
            Kirim Link Reset
        </button>
    </form>
</body>

</html>
