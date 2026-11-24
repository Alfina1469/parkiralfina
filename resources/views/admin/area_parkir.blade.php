<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area Parkir - Sistem Parkir</title>
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
                <a href="{{ route('admin.kendaraan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
                    🚘 <span>Kendaraan</span>
                </a>
                <!-- Menu Area Parkir (Aktif) -->
                <a href="{{ route('admin.area.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium shadow-lg shadow-blue-600/30">
                    🅿️ <span>Area Parkir</span>
                </a>
                <a href="{{ route('admin.tarif.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">
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
            <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-red-400 hover:bg-red-500/10 transition">
                🚪 <span>Keluar</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <header class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-white">Data Area Parkir</h2>
                <p class="text-sm text-gray-400">Manajemen titik lokasi dan kapasitas parkir (`tb_area_parkir`).</p>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Input Area Baru -->
            <div class="bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6 h-fit">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    ➕ <span>Tambah Area Parkir</span>
                </h3>
                
                <form action="/admin/area-parkir" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Nama Area</label>
                        <input type="text" name="nama_area" placeholder="Contoh: Gedung A - Lantai 1" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Kapasitas Maksimal</label>
                        <input type="number" name="kapasitas" placeholder="Contoh: 50" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Jumlah Terisi</label>
                        <input type="number" name="terisi" placeholder="Contoh: 20" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-blue-600/30">
                        Simpan Area
                    </button>
                </form>
            </div>

            <!-- Tabel Data Area Parkir -->
            <div class="lg:col-span-2 bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6">
                <h3 class="text-lg font-bold text-white mb-4">Daftar Area Parkir</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-700/60 text-gray-400 text-xs uppercase tracking-wider">
                                <th class="py-3 px-4">ID Area</th>
                                <th class="py-3 px-4">Nama Area</th>
                                <th class="py-3 px-4">Kapasitas</th>
                                <th class="py-3 px-4">Terisi</th>
                                <th class="py-3 px-4">Sisa Slot</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/40 text-sm">
                            @forelse($areaParkir as $item)
                            @php
                                $sisa = $item['kapasitas'] - $item['terisi'];
                            @endphp
                            <tr>
                                <td class="py-3.5 px-4 font-mono text-gray-400">#{{ $item['id_area'] }}</td>
                                <td class="py-3.5 px-4 font-semibold text-white">{{ $item['nama_area'] }}</td>
                                <td class="py-3.5 px-4 text-gray-300">{{ $item['kapasitas'] }} Slot</td>
                                <td class="py-3.5 px-4 text-amber-400 font-medium">{{ $item['terisi'] }} Slot</td>
                                <td class="py-3.5 px-4 font-medium {{ $sisa > 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                    {{ $sisa }} Slot
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Tombol Edit -->
                                        <button type="button" 
                                                onclick="openEditModal({{ json_encode($item) }})" 
                                                class="bg-yellow-500/20 text-yellow-400 hover:bg-yellow-500 hover:text-white px-3 py-1 rounded-lg text-xs font-semibold transition">
                                            Edit
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form action="/admin/area-parkir/{{ $item['id_area'] }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus area ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-medium bg-red-400/10 px-2.5 py-1.5 rounded-lg border border-red-400/20 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-gray-400">Belum ada data area parkir.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Pop-up Edit Area Parkir -->
    <div id="editModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl p-6 w-full max-w-md shadow-2xl">
            <h3 class="text-lg font-bold text-white mb-4">Edit Area Parkir</h3>
            
            <form id="editForm" action="" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Nama Area</label>
                    <input type="text" id="edit_nama_area" name="nama_area" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Kapasitas Maksimal</label>
                    <input type="number" id="edit_kapasitas" name="kapasitas" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Jumlah Terisi</label>
                    <input type="number" id="edit_terisi" name="terisi" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeEditModal()" class="w-1/2 bg-slate-800 hover:bg-slate-700 text-gray-300 font-semibold py-2.5 rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" class="w-1/2 bg-yellow-500 hover:bg-yellow-400 text-slate-950 font-bold py-2.5 rounded-xl transition">
                        Update Area
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script JavaScript Modal -->
    <script>
        function openEditModal(area) {
            const form = document.getElementById('editForm');
            form.action = `/admin/area-parkir/${area.id_area}`;

            document.getElementById('edit_nama_area').value = area.nama_area;
            document.getElementById('edit_kapasitas').value = area.kapasitas;
            document.getElementById('edit_terisi').value = area.terisi;

            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('flex');
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>

</body>
</html>