<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan - Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%); }
        @media print {
            aside, .no-print { display: none !important; }
            main { padding: 0 !important; }
            body { background: white !important; color: black !important; }
            .card-box { border: 1px solid #ccc !important; background: transparent !important; color: black !important; }
        }
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
                <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    📊 <span>Dashboard</span>
                </a>
                <a href="{{ route('owner.laporan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium shadow-lg shadow-blue-600/30">
                    📈 <span>Laporan Pendapatan</span>
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
        <header class="flex justify-between items-center mb-8 no-print">
            <div>
                <h2 class="text-2xl font-bold text-white">Laporan Rekap Pendapatan</h2>
                <p class="text-sm text-gray-400">Ringkasan transaksi dan total pendapatan untuk Owner.</p>
            </div>
            <button onclick="window.print()" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl transition shadow-lg shadow-emerald-600/30 flex items-center gap-2">
                🖨️ <span>Cetak Laporan</span>
            </button>
        </header>

        <!-- Ringkasan Card -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="card-box bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6">
                <p class="text-sm text-gray-400 mb-1">Total Pendapatan</p>
                <h3 class="text-3xl font-bold text-green-400">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</h3>
            </div>
            <div class="card-box bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6">
                <p class="text-sm text-gray-400 mb-1">Total Transaksi Selesai</p>
                <h3 class="text-3xl font-bold text-blue-400">{{ $totalTransaksi ?? 0 }} Kendaraan</h3>
            </div>
        </div>

        <!-- Tabel Laporan -->
        <div class="card-box bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6">
            <h3 class="text-lg font-bold text-white mb-4">Rincian Transaksi Parkir</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-700/60 text-gray-400 text-xs uppercase tracking-wider">
                            <th class="py-3 px-4">ID Parkir</th>
                            <th class="py-3 px-4">Plat Nomor</th>
                            <th class="py-3 px-4">Waktu Masuk</th>
                            <th class="py-3 px-4">Waktu Keluar</th>
                            <th class="py-3 px-4">Durasi</th>
                            <th class="py-3 px-4">Biaya Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/40 text-sm">
                        @forelse($laporans as $item)
                        <tr>
                            <td class="py-3.5 px-4 font-mono text-gray-400">#{{ $item->id_parkir }}</td>
                            <td class="py-3.5 px-4 font-bold text-white">{{ $item->kendaraan->plat_nomor ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-gray-300 text-xs">{{ $item->waktu_masuk }}</td>
                            <td class="py-3.5 px-4 text-gray-300 text-xs">{{ $item->waktu_keluar ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-gray-300">{{ $item->durasi_jam ? $item->durasi_jam . ' Jam' : '-' }}</td>
                            <td class="py-3.5 px-4 text-green-400 font-semibold">Rp {{ number_format($item->biaya_total ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-400">Belum ada data transaksi yang selesai.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>