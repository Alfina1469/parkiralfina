<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Parkir</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 flex items-center justify-center h-screen text-white">
    <div class="w-full max-w-md bg-slate-800 p-8 rounded-2xl shadow-lg border border-slate-700">
        <h2 class="text-2xl font-bold text-center mb-6">Login Sistem Parkir</h2>

        @if($errors->any())
            <div class="bg-red-500/20 border border-red-500 text-red-300 p-3 rounded-xl mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm mb-1 text-gray-400">Username</label>
                <input type="text" name="username" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white focus:outline-none focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm mb-1 text-gray-400">Password</label>
                <input type="password" name="password" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white focus:outline-none focus:border-blue-500" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 font-bold py-3 rounded-xl transition mt-4">
                Masuk
            </button>
        </form>
    </div>
</body>
</html>