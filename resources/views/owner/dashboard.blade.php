<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Parkir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%);
        }
    </style>
</head>
<body class="font-sans text-gray-100 min-h-screen flex">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-slate-900/80 backdrop-blur-md border-r border-slate-700/50 p-6 flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-3 mb-8">
                <div class="bg-blue-600 p-2.5 rounded-xl text-white">
                    🚗
                </div>
                <h1 class="font-bold text-lg text-white tracking-wide">Parkir System</h1>
            </div>
            
            <nav class="space-y-2">
                <!-- Dashboard (Semua Role) -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-gray-400">
                    📊 <span>Dashboard</span>
                </a>

                <!-- KHUSUS ADMIN -->
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.kendaraan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-gray-400">
                        🚘 <span>Kendaraan</span>
                    </a>
                    <a href="{{ route('admin.area.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-gray-400">
                        🅿️ <span>Area Parkir</span>
                    </a>
                    <a href="{{ route('admin.tarif.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-gray-400">
                        🏷️ <span>Tarif</span>
                    </a>
                    <a href="{{ route('admin.user.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-gray-400">
                        👥 <span>Kelola User</span>
                    </a>
                    <a href="{{ route('admin.log.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-gray-400">
                        📜 <span>Log Aktivitas</span>
                    </a>
                @endif

                <!-- KHUSUS PETUGAS -->
                @if(Auth::user()->role === 'petugas')
                    <a href="{{ route('petugas.transaksi.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-gray-400">
                        💳 <span>Transaksi</span>
                    </a>
                @endif

                <!-- KHUSUS OWNER -->
                @if(Auth::user()->role === 'owner')
                    <a href="{{ route('owner.laporan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-gray-400">
                        📈 <span>Laporan</span>
                    </a>
                @endif
            </nav>
        </div>

        <div class="pt-6 border-t border-slate-800">
            <a href="/login" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-red-400 hover:bg-red-500/10 transition">
                🚪 <span>Keluar</span>
            </a>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 p-8 overflow-y-auto">
        <!-- HEADER -->
        <header class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-white">Dashboard Utama</h2>
                <p class="text-sm text-gray-400">Ringkasan aktivitas dan statistik parkir hari ini.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="bg-slate-800/80 px-4 py-2 rounded-xl border border-slate-700/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center font-bold text-white text-sm">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <span class="text-sm font-medium">{{ Auth::user()->name ?? 'Admin Parkir' }}</span>
                </div>
            </div>
        </header>

        <!-- STATS CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-slate-800/50 backdrop-blur-md border border-slate-700/50 p-6 rounded-2xl">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-xs uppercase tracking-wider text-gray-400 font-semibold">Spot Tersedia</p>
                        <h3 class="text-3xl font-bold text-white mt-1">{{ $slotTersedia ?? 145 }} <span class="text-sm text-gray-400 font-normal">/ {{ $totalSlot ?? 300 }}</span></h3>
                    </div>
                    <span class="p-2 bg-blue-500/10 text-blue-400 rounded-lg text-xl">🅿️</span>
                </div>
                <div class="w-full bg-slate-700/50 h-2 rounded-full overflow-hidden">
                    <div class="bg-blue-500 h-full rounded-full" style="width: {{ $persentaseTerisi ?? 51.6 }}%;"></div>
                </div>
                <p class="text-xs text-gray-400 mt-2">Terisi {{ $persentaseTerisi ?? 51.6 }}% dari total slot</p>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-md border border-slate-700/50 p-6 rounded-2xl flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wider text-gray-400 font-semibold">Kendaraan Aktif</p>
                    <h3 class="text-3xl font-bold text-white mt-1">{{ $slotTerisi ?? 155 }} Unit</h3>
                    <p class="text-xs text-emerald-400 mt-2 flex items-center gap-1">● Live Update System</p>
                </div>
                <div class="w-16 h-16 flex items-center justify-center bg-blue-500/10 text-blue-400 rounded-full font-bold text-lg border border-blue-500/20">
                    {{ $persentaseTerisi ?? 48 }}%
                </div>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-md border border-slate-700/50 p-6 rounded-2xl">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-xs uppercase tracking-wider text-gray-400 font-semibold">Pendapatan Hari Ini</p>
                        <h3 class="text-2xl font-bold text-emerald-400 mt-1">Rp {{ number_format($pendapatanHariIni ?? 3250000, 0, ',', '.') }}</h3>
                    </div>
                    <span class="p-2 bg-emerald-500/10 text-emerald-400 rounded-lg text-xl">💰</span>
                </div>
                <p class="text-xs text-emerald-400 font-medium">↑ Data Terkini</p>
            </div>
        </div>

        <!-- CHARTS -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2 bg-slate-800/50 backdrop-blur-md border border-slate-700/50 p-6 rounded-2xl">
                <h4 class="text-md font-bold text-white mb-4">Tren Aktivitas Jam Parkir (Hari Ini)</h4>
                <div class="h-64">
                    <canvas id="trafficChart"></canvas>
                </div>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-md border border-slate-700/50 p-6 rounded-2xl">
                <h4 class="text-md font-bold text-white mb-4">Distribusi Jenis Kendaraan</h4>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="vehicleChart"></canvas>
                </div>
            </div>
        </div>

        <!-- TABEL AKTIVITAS -->
        <div class="bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6">
            <h4 class="text-md font-bold text-white mb-4">Aktivitas Terkini</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-700/60 text-gray-400 text-xs uppercase tracking-wider">
                            <th class="py-3 px-4">Plat Nomor</th>
                            <th class="py-3 px-4">Jenis</th>
                            <th class="py-3 px-4">Jam Masuk</th>
                            <th class="py-3 px-4">Jam Keluar</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/40 text-sm">
                        @forelse($aktivitasTerkini as $item)
                             <tr class="hover:bg-slate-800/30 transition">
                                <td class="py-3.5 px-4 font-semibold text-white">
                                    {{ $item->kendaraan->plat_nomor ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-gray-300">
                                    {{ ucfirst($item->kendaraan->jenis_kendaraan ?? '-') }}
                                </td>
                                <td class="py-3.5 px-4 text-gray-300">
                                    {{ \Carbon\Carbon::parse($item->waktu_masuk)->format('H:i') }}
                                </td>
                                <td class="py-3.5 px-4 text-gray-400">
                                    {{ $item->waktu_keluar ? \Carbon\Carbon::parse($item->waktu_keluar)->format('H:i') : '-' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($item->status == 'masuk')
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                                            Parkir
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-rose-500/15 text-rose-400 border border-rose-500/30">
                                            Keluar
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 px-4 text-center text-gray-400">
                                    Belum ada aktivitas parkir.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- SCRIPT CHART.JS -->
    <script>
        const ctxTraffic = document.getElementById('trafficChart').getContext('2d');
        new Chart(ctxTraffic, {
            type: 'line',
            data: {
                labels: ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'],
                datasets: [
                    {
                        label: 'Masuk',
                        data: [40, 65, 85, 120, 95, 110, 70],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Keluar',
                        data: [10, 25, 60, 90, 80, 105, 95],
                        borderColor: '#06b6d4',
                        backgroundColor: 'transparent',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#94a3b8' } } },
                scales: {
                    x: { ticks: { color: '#94a3b8' }, grid: { color: '#334155' } },
                    y: { ticks: { color: '#94a3b8' }, grid: { color: '#334155' } }
                }
            }
        });

        const ctxVehicle = document.getElementById('vehicleChart').getContext('2d');
        new Chart(ctxVehicle, {
            type: 'doughnut',
            data: {
                labels: ['Mobil', 'Motor', 'Lainnya'],
                datasets: [{
                    data: [55, 35, 10],
                    backgroundColor: ['#3b82f6', '#06b6d4', '#64748b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { color: '#94a3b8' } } }
            }
        });
    </script>
</body>
</html>