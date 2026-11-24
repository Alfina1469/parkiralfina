<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Tarif - Sistem Parkir</title>
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
                <a href="{{ route('admin.tarif.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium shadow-lg shadow-blue-600/30">
                    🏷️ <span>Tarif</span>
                </a>
                <a href="{{ route('admin.user.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    👥 <span>Kelola User</span>
                </a>
                <a href="{{ route('admin.log.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    📜 <span>Log Aktivitas</span>
                </a>
            </nav>
        </div>

        <div class="pt-6 border-t border-slate-800">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-red-400 hover:bg-red-500/10 transition">
                    🚪 <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <header class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-white">Master Tarif Parkir</h2>
                <p class="text-sm text-gray-400">Pengaturan biaya parkir per jenis kendaraan (`tb_tarif`).</p>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Input Tarif Baru -->
            <div class="bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6 h-fit">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    ➕ <span>Tambah Tarif Baru</span>
                </h3>
                
                <form action="{{ route('admin.tarif.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Jenis Kendaraan</label>
                        <input type="text" name="jenis_kendaraan" placeholder="Contoh: Mobil / Motor / Bus" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Tarif Per Jam (Rp)</label>
                        <input type="number" name="tarif_per_jam" placeholder="Contoh: 3000" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Tarif Maksimal / Hari (Rp)</label>
                        <input type="number" name="tarif_maksimal" placeholder="Contoh: 25000" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-blue-600/30">
                        Simpan Tarif
                    </button>
                </form>
            </div>

            <!-- Tabel Data Tarif -->
            <div class="lg:col-span-2 bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6">
                <h3 class="text-lg font-bold text-white mb-4">Daftar Tarif Parkir</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-700/60 text-gray-400 text-xs uppercase tracking-wider">
                                <th class="py-3 px-4">ID Tarif</th>
                                <th class="py-3 px-4">Jenis Kendaraan</th>
                                <th class="py-3 px-4">Tarif Per Jam</th>
                                <th class="py-3 px-4">Tarif Maksimal</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/40 text-sm">
                            @foreach($tarif as $item)
                            <tr>
                                <td class="py-3.5 px-4 font-mono text-gray-400">#{{ $item['id_tarif'] }}</td>
                                <td class="py-3.5 px-4 font-semibold text-white">{{ $item['jenis_kendaraan'] }}</td>
                                <td class="py-3.5 px-4 text-emerald-400 font-medium">Rp {{ number_format($item['tarif_per_jam'], 0, ',', '.') }}</td>
                                <td class="py-3.5 px-4 text-blue-400 font-medium">Rp {{ number_format($item['tarif_maksimal'], 0, ',', '.') }}</td>
                                <td class="py-3.5 px-4 text-center flex justify-center items-center gap-2">
                                    <!-- Tombol Edit Modal -->
                                    <button onclick="openEditModal('{{ $item['id_tarif'] }}', '{{ $item['jenis_kendaraan'] }}', '{{ $item['tarif_per_jam'] }}', '{{ $item['tarif_maksimal'] }}')" class="text-yellow-400 hover:text-yellow-300 text-xs font-medium bg-yellow-500/10 px-3 py-1.5 rounded-lg border border-yellow-500/20">Edit</button>
                                    
                                    <!-- Form Hapus -->
                                    <form action="{{ route('admin.tarif.destroy', $item['id_tarif']) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tarif ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-medium bg-red-500/10 px-3 py-1.5 rounded-lg border border-red-500/20">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Edit Tarif -->
    <div id="editModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">
        <div class="bg-slate-800 border border-slate-700 rounded-2xl w-full max-w-md p-6 shadow-2xl">
            <h3 class="text-lg font-bold text-white mb-4">Edit Data Tarif</h3>
            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Jenis Kendaraan</label>
                    <input type="text" id="edit_jenis" name="jenis_kendaraan" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Tarif Per Jam (Rp)</label>
                    <input type="number" id="edit_per_jam" name="tarif_per_jam" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Tarif Maksimal (Rp)</label>
                    <input type="number" id="edit_maksimal" name="tarif_maksimal" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl text-gray-400 hover:bg-slate-700 text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, jenis, perJam, maksimal) {
            document.getElementById('editForm').action = '/admin/tarif/' + id;
            document.getElementById('edit_jenis').value = jenis;
            document.getElementById('edit_per_jam').value = perJam;
            document.getElementById('edit_maksimal').value = maksimal;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>

</body>
</html>