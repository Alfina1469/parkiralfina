<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Kendaraan - Sistem Parkir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans text-gray-100 min-h-screen flex" x-data="{ openEdit: false, editData: {} }">

    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-slate-900/80 backdrop-blur-md border-r border-slate-700/50 p-6 flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-3 mb-8">
                <div class="bg-blue-600 p-2.5 rounded-xl text-white">🚗</div>
                <h1 class="font-bold text-lg text-white tracking-wide">Parkir System</h1>
            </div>
            
            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">📊 <span>Dashboard</span></a>
                <a href="{{ route('petugas.transaksi.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">💳 <span>Transaksi</span></a>
                <a href="{{ route('admin.kendaraan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium shadow-lg shadow-blue-600/30">🚘 <span>Kendaraan</span></a>
                <a href="{{ route('admin.area.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">🅿️ <span>Area Parkir</span></a>
                <a href="{{ route('admin.tarif.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">🏷️ <span>Tarif</span></a>
                <a href="{{ route('admin.user.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">👥 <span>Kelola User</span></a>
                <a href="{{ route('admin.log.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">📜 <span>Log Aktivitas</span></a>
            </nav>
        </div>

        <div class="pt-6 border-t border-slate-800">
            <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-red-400 hover:bg-red-500/10 transition">🚪 <span>Keluar</span></a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <header class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-white">Master Kendaraan</h2>
                <p class="text-sm text-gray-400">Pencatatan data kendaraan terdaftar (`tb_kendaraan`).</p>
            </div>
        </header>

        <!-- Pesan Sukses -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Input Kendaraan Baru -->
            <div class="bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6 h-fit">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    ➕ <span>Tambah Kendaraan</span>
                </h3>
                
                <form action="{{ route('admin.kendaraan.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Plat Nomor</label>
                        <input type="text" name="plat_nomor" placeholder="Contoh: B 1234 ABC" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 uppercase" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Jenis Kendaraan</label>
                        <select name="jenis_kendaraan" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500">
                            <option value="Mobil">Mobil</option>
                            <option value="Motor">Motor</option>
                            <option value="Truk / Bus">Truk / Bus</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Warna</label>
                        <input type="text" name="warna" placeholder="Contoh: Hitam / Putih" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Nama Pemilik</label>
                        <input type="text" name="pemilik" placeholder="Contoh: Ahmad" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-blue-600/30">
                        Simpan Kendaraan
                    </button>
                </form>
            </div>

            <!-- Tabel Data Kendaraan -->
            <div class="lg:col-span-2 bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6">
                <h3 class="text-lg font-bold text-white mb-4">Daftar Kendaraan Terdaftar</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-700/60 text-gray-400 text-xs uppercase tracking-wider">
                                <th class="py-3 px-4">ID</th>
                                <th class="py-3 px-4">Plat Nomor</th>
                                <th class="py-3 px-4">Jenis</th>
                                <th class="py-3 px-4">Warna</th>
                                <th class="py-3 px-4">Pemilik</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/40 text-sm">
                            @forelse ($kendaraan as $item)
                            <tr>
                                <td class="py-3.5 px-4 font-mono text-gray-400">#{{ $loop->iteration }}</td>
                                <td class="py-3.5 px-4 font-bold text-blue-400 font-mono">{{ $item->plat_nomor }}</td>
                                <td class="py-3.5 px-4 text-white">{{ $item->jenis_kendaraan }}</td>
                                <td class="py-3.5 px-4 text-gray-300">{{ $item->warna }}</td>
                                <td class="py-3.5 px-4 text-gray-300">{{ $item->pemilik }}</td>
                                <td class="py-3.5 px-4 text-center flex items-center justify-center gap-2">
                                    <!-- Tombol Edit -->
                                    <button 
                                        @click="openEdit = true; editData = { 
                                            id: '{{ $item->id_kendaraan }}', 
                                            plat_nomor: '{{ $item->plat_nomor }}', 
                                            jenis_kendaraan: '{{ $item->jenis_kendaraan }}', 
                                            warna: '{{ $item->warna }}', 
                                            pemilik: '{{ $item->pemilik }}' 
                                        }" 
                                        class="text-yellow-400 hover:text-yellow-300 text-xs font-medium bg-yellow-400/10 px-3 py-1.5 rounded-lg border border-yellow-400/20">
                                        Edit
                                    </button>
            
                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.kendaraan.destroy', $item->id_kendaraan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kendaraan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-medium bg-red-400/10 px-3 py-1.5 rounded-lg border border-red-400/20">
                                             Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-gray-400">Belum ada data kendaraan di database.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Popup Edit Data Kendaraan -->
        <div x-show="openEdit" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 z-50" x-cloak>
            <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 w-full max-w-md shadow-2xl" @click.away="openEdit = false">
                <h3 class="text-lg font-bold text-white mb-4">Edit Data Kendaraan</h3>
                
                <form :action="'/admin/kendaraan/' + editData.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Plat Nomor</label>
                        <input type="text" name="plat_nomor" x-model="editData.plat_nomor" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white uppercase focus:outline-none focus:border-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Jenis Kendaraan</label>
                        <select name="jenis_kendaraan" x-model="editData.jenis_kendaraan" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500">
                            <option value="Mobil">Mobil</option>
                            <option value="Motor">Motor</option>
                            <option value="Truk / Bus">Truk / Bus</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Warna</label>
                        <input type="text" name="warna" x-model="editData.warna" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Nama Pemilik</label>
                        <input type="text" name="pemilik" x-model="editData.pemilik" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="openEdit = false" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-xl text-gray-300 text-sm">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-xl text-white font-bold text-sm">Update Kendaraan</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

</body>
</html>