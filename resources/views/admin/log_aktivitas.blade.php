<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas - Sistem Parkir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%); }
    </style>
</head>
<body class="font-sans text-gray-100 min-h-screen flex">

    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-slate-900/80 backdrop-blur-md border-r border-slate-700/50 p-6 flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-3 mb-8">
                <div class="bg-blue-600 p-2.5 rounded-xl text-white">🚗</div>
                <h1 class="font-bold text-lg text-white tracking-wide">Parkir System</h1>
            </div>
            
            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    📊 <span>Dashboard</span>
                </a>
                <a href="{{ route('petugas.transaksi.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    💳 <span>Transaksi</span>
                </a>
                <a href="{{ route('admin.kendaraan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    🚘 <span>Kendaraan</span>
                </a>
                <a href="{{ route('admin.area.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    🅿️ <span>Area Parkir</span>
                </a>
                <a href="{{ route('admin.tarif.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    🏷️ <span>Tarif</span>
                </a>
                <a href="{{ route('admin.user.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    👥 <span>Kelola User</span>
                </a>
                <a href="{{ route('admin.log.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium shadow-lg shadow-blue-600/30">
                    📜 <span>Log Aktivitas</span>
                </a>
            </nav>
        </div>

        <div class="pt-6 border-t border-slate-800">
            <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-red-400 hover:bg-red-500/10 transition">
                🚪 <span>Keluar</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <header class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-white">Log Aktivitas Sistem</h2>
                <p class="text-sm text-gray-400">Riwayat audit jejak aktivitas pengguna (`tb_log_aktivitas`).</p>
            </div>
            <div>
                <button onclick="window.location.reload()" class="bg-slate-800 hover:bg-slate-700 text-gray-300 px-4 py-2 rounded-xl text-sm border border-slate-700 transition flex items-center gap-2">
                    🔄 <span>Refresh Log</span>
                </button>
            </div>
        </header>

        <!-- Tabel Log Aktivitas -->
        <div class="bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-700/60 text-gray-400 text-xs uppercase tracking-wider">
                            <th class="py-3.5 px-4">ID Log</th>
                            <th class="py-3.5 px-4">Pengguna</th>
                            <th class="py-3.5 px-4">Deskripsi Aktivitas</th>
                            <th class="py-3.5 px-4">Waktu Kejadian</th>
                        </tr>
                    </thead>
                  <tbody class="divide-y divide-slate-700/40 text-sm">
                @forelse($logAktivitas as $log)
                    <tr>
                        <td class="py-4 px-4 font-mono text-gray-400">#{{ $log->id_log }}</td>
                        <td class="py-4 px-4">
                            <span class="font-semibold text-blue-400 bg-blue-500/10 px-2.5 py-1 rounded-lg border border-blue-500/20">
                                👤 {{ $log->user->nama ?? $log->user->username ?? 'petugas' }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-gray-200">{{ $log->aktivitas }}</td>
                        <td class="py-4 px-4 font-mono text-gray-400 text-xs">{{ $log->waktu_aktivitas }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-400 font-medium">
                            📬 Belum ada riwayat log aktivitas tercatat.
                        </td>
                    </tr>
                @endforelse
                </tbody> 
                </table>
            </div>
        </div>
    </main>

</body>
</html>