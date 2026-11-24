<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi - Sistem Parkir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%); }
    </style>
</head>
<body class="font-sans text-gray-100 min-h-screen flex">

    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-slate-900/80 backdrop-blur-md border-r border-slate-700/50 p-6 flex flex-col justify-between">
        <div>
            <!-- Logo & Title -->
            <div class="flex items-center gap-3 mb-8">
                <div class="bg-blue-600 p-2.5 rounded-xl text-white">🚗</div>
                <h1 class="font-bold text-lg text-white tracking-wide">Parkir System</h1>
            </div>
            
            <!-- Navigation Links -->
            <nav class="space-y-2">
                <a href="{{ route('petugas.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    📊 <span>Dashboard Utama</span>
                </a>
                <a href="{{ route('petugas.transaksi.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium shadow-lg shadow-blue-600/30">
                    💳 <span>Kelola Transaksi</span>
                </a>
            </nav>
        </div>

        <!-- Logout Button -->
        <div class="pt-6 border-t border-slate-800">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-red-400 hover:bg-red-500/10 transition text-left">
                    🚪 <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 p-8 overflow-y-auto">
        <!-- Header -->
        <header class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-white">Kelola Transaksi Parkir</h2>
                <p class="text-sm text-gray-400">Pencatatan kendaraan masuk, keluar, dan riwayat transaksi.</p>
            </div>
        </header>

        <!-- Alert Notifikasi Sukses / Error -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 flex items-center justify-between">
                <span>✅ {{ session('success') }}</span>
                @if(session('print_id'))
                    <a href="{{ route('petugas.transaksi.cetak', session('print_id')) }}" target="_blank" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-lg transition shadow">
                        🖨️ Buka Struk Sekarang
                    </a>
                @endif
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-500/20 border border-red-500/40 text-red-300">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-500/20 border border-red-500/40 text-red-300">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Layout Grid: Form & Tabel -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Form Input Kendaraan Masuk -->
            <div class="bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6 h-fit">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    ➕ <span>Input Kendaraan Masuk</span>
                </h3>
                
                <form action="{{ route('petugas.transaksi.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Plat Nomor</label>
                        <input type="text" name="plat_nomor" list="daftar_kendaraan" placeholder="Contoh: B 1234 ABC" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 uppercase" required>

                        @if(isset($masterKendaraan))
                        <datalist id="daftar_kendaraan">
                            @foreach($masterKendaraan as $k)
                                <option value="{{ $k->plat_nomor }}">{{ $k->jenis_kendaraan ?? '' }} {{ $k->pemilik ?? '' }}</option>
                            @endforeach
                        </datalist>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Jenis Kendaraan</label>
                        <select name="jenis_kendaraan" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500">
                            <option value="Mobil">🚗 Mobil</option>
                            <option value="Motor">🏍️ Motor</option>
                            <option value="Lainnya">🚙 Lainnya</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-blue-600/30">
                        Cetak Tiket Parkir
                    </button>
                </form>
            </div>

            <!-- Tabel Daftar Transaksi -->
            <div class="lg:col-span-2 bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6">
                <h3 class="text-lg font-bold text-white mb-4">Daftar Transaksi</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-700/60 text-gray-400 text-xs uppercase tracking-wider">
                                <th class="py-3 px-4">ID Parkir</th>
                                <th class="py-3 px-4">ID Kendaraan</th>
                                <th class="py-3 px-4">Plat Nomor</th>
                                <th class="py-3 px-4">Waktu Masuk</th>
                                <th class="py-3 px-4">Waktu Keluar</th>
                                <th class="py-3 px-4">Durasi</th>
                                <th class="py-3 px-4">Biaya Total</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/40 text-sm">
                            @forelse($transaksi as $item)
                            <tr class="hover:bg-slate-800/30 transition">
                                <td class="py-3.5 px-4 font-mono text-gray-400">#{{ $item->id_parkir }}</td>
                                <td class="py-3.5 px-4 text-gray-300">#{{ $item->id_kendaraan }}</td>
                                <td class="py-3.5 px-4 font-bold text-white">{{ $item->kendaraan->plat_nomor ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-gray-300 text-xs">{{ $item->waktu_masuk }}</td>
                                <td class="py-3.5 px-4 text-gray-300 text-xs">{{ $item->waktu_keluar ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-gray-300">{{ ($item->durasi_jam ?? $item->durasi) ? ($item->durasi_jam ?? $item->durasi) . ' Jam' : '-' }}</td>
                                <td class="py-3.5 px-4 font-semibold text-emerald-400">
                                    {{ $item->biaya_total ? 'Rp ' . number_format($item->biaya_total, 0, ',', '.') : '-' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($item->status == 'masuk')
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                            Masuk
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                            Keluar
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 flex items-center gap-2">
                                    <!-- Tombol Cetak Struk -->
                                    <a href="{{ route('petugas.transaksi.cetak', $item->id_parkir) }}" target="_blank" class="px-2.5 py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold rounded-lg transition shadow whitespace-nowrap">
                                        🖨️ Cetak
                                    </a>

                                    <!-- Tombol Proses Keluar -->
                                    @if($item->status == 'masuk')
                                        <form action="{{ route('petugas.transaksi.keluar', $item->id_parkir) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" onclick="return confirm('Proses kendaraan keluar?')" class="px-3 py-1.5 bg-red-600 hover:bg-red-500 text-white text-xs font-semibold rounded-lg transition shadow-md whitespace-nowrap">
                                                🚪 Keluar
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="py-6 px-4 text-center text-gray-400">Belum ada transaksi parkir.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    @if(session('print_id'))
    <script>
        window.open("{{ route('petugas.transaksi.cetak', session('print_id')) }}", "_blank");
    </script>
    @endif

</body>
</html>