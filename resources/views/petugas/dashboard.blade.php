<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - Parkir System</title>
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
                <a href="{{ route('petugas.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium shadow-lg shadow-blue-600/30">
                    📊 <span>Dashboard Utama</span>
                </a>
                <a href="{{ route('petugas.transaksi.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    💳 <span>Kelola Transaksi</span>
                </a>
            </nav>
        </div>

        <div class="pt-6 border-t border-slate-800">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-red-400 hover:bg-red-500/10 transition text-left">
                    🚪 <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <header class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-white">Dashboard Petugas</h2>
                <p class="text-sm text-gray-400">Selamat bertugas! Ringkasan operasional parkir hari ini.</p>
            </div>
            <a href="{{ route('petugas.transaksi.index') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-xl transition shadow-lg shadow-blue-600/30 flex items-center gap-2">
                ➕ <span>Input Transaksi Baru</span>
            </a>
        </header>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Masuk Hari Ini</p>
                <h3 class="text-3xl font-bold text-white">{{ $totalKendaraanHariIni }} <span class="text-sm font-normal text-gray-400">Kendaraan</span></h3>
            </div>
            <div class="bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Sedang Parkir (Aktif)</p>
                <h3 class="text-3xl font-bold text-blue-400">{{ $kendaraanSedangParkir }} <span class="text-sm font-normal text-gray-400">Kendaraan</span></h3>
            </div>
            <div class="bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Selesai / Keluar Hari Ini</p>
                <h3 class="text-3xl font-bold text-emerald-400">{{ $kendaraanSudahKeluar }} <span class="text-sm font-normal text-gray-400">Kendaraan</span></h3>
            </div>
        </div>

        <!-- Monitoring Transaksi Terkini -->
        <div class="bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6">
            <h3 class="text-lg font-bold text-white mb-4">Aktivitas Parkir Terbaru</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-700/60 text-gray-400 text-xs uppercase tracking-wider">
                            <th class="py-3 px-4">ID Parkir</th>
                            <th class="py-3 px-4">Plat Nomor</th>
                            <th class="py-3 px-4">Waktu Masuk</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/40 text-sm">
                        @forelse($recentTransaksi as $item)
                        <tr>
                            <td class="py-3.5 px-4 font-mono text-gray-400">#{{ $item->id_parkir }}</td>
                            <td class="py-3.5 px-4 font-bold text-white">{{ $item->kendaraan->plat_nomor ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-gray-300 text-xs">{{ $item->waktu_masuk }}</td>
                            <td class="py-3.5 px-4">
                                @if($item->status == 'masuk')
                                    <span class="px-2.5 py-1 text-xs rounded bg-blue-500/20 text-blue-400 font-medium">masuk</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs rounded bg-emerald-500/20 text-emerald-400 font-medium">keluar</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-gray-400">Belum ada aktivitas parkir.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>