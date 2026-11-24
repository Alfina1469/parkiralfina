<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User - Sistem Parkir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%); }
    </style>
</head>
<body class="font-sans text-gray-100 min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900/80 backdrop-blur-md border-r border-slate-700/50 p-6 flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-3 mb-8">
                <div class="bg-blue-600 p-2.5 rounded-xl text-white">🚗</div>
                <h1 class="font-bold text-lg text-white tracking-wide">Parkir System</h1>
            </div>
            
            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">📊 <span>Dashboard</span></a>
                <a href="{{ route('petugas.transaksi.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">💳 <span>Transaksi</span></a>
                <a href="{{ route('admin.kendaraan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">🚘 <span>Kendaraan</span></a>
                <a href="{{ route('admin.area.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">🅿️ <span>Area Parkir</span></a>
                <a href="{{ route('admin.tarif.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">🏷️ <span>Tarif</span></a>
                <a href="{{ route('admin.user.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white font-medium shadow-lg shadow-blue-600/30">👥 <span>Kelola User</span></a>
                <a href="{{ route('admin.log.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-slate-800 hover:text-white transition">📜 <span>Log Aktivitas</span></a>
            </nav>
        </div>

        <div class="pt-6 border-t border-slate-800">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-red-400 hover:bg-red-500/10 transition">🚪 <span>Keluar</span></button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <header class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-white">Kelola User / Pengguna</h2>
                <p class="text-sm text-gray-400">Manajemen pengguna sistem (Admin & Petugas).</p>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Input User Baru -->
            <div class="bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6 h-fit">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">➕ <span>Tambah User Baru</span></h3>
                
                <form action="{{ route('admin.user.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Nama Lengkap</label>
                        <input type="text" name="nama" placeholder="Masukkan nama" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Username</label>
                        <input type="text" name="username" placeholder="Masukkan username" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Role</label>
                        <select name="role" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                            <option value="Admin">Admin</option>
                            <option value="Petugas">Petugas</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-blue-600/30">
                        Simpan User
                    </button>
                </form>
            </div>

            <!-- Tabel Data User -->
            <div class="lg:col-span-2 bg-slate-800/50 backdrop-blur-md border border-slate-700/50 rounded-2xl p-6">
                <h3 class="text-lg font-bold text-white mb-4">Daftar User</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-700/60 text-gray-400 text-xs uppercase tracking-wider">
                                <th class="py-3 px-4">ID</th>
                                <th class="py-3 px-4">Nama</th>
                                <th class="py-3 px-4">Username</th>
                                <th class="py-3 px-4">Role</th>
                                <th class="py-3 px-4">Tanggal Buat</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/40 text-sm">
                           @foreach($users as $user)
                            <tr>
                                <td class="py-3.5 px-4 font-mono text-gray-400">#{{ $loop->iteration }}</td>
    
                                <!-- DIBETULKAN: Menggunakan 'nama_lengkap' sesuai database -->
                                <td class="py-3.5 px-4 font-semibold text-white">{{ $user['nama_lengkap'] ?? $user->nama_lengkap }}</td>
    
                                <td class="py-3.5 px-4 text-gray-300">{{ $user['username'] ?? $user->username }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ ($user['role'] ?? $user->role) == 'admin' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 'bg-blue-500/20 text-blue-300 border border-blue-500/30' }}">
                                        {{ $user['role'] ?? $user->role }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-gray-400 text-xs">{{ $user['created_at'] ?? now()->format('Y-m-d') }}</td>
                                <td class="py-3.5 px-4 text-center flex justify-center items-center gap-2">
                                    <!-- DIBETULKAN: Menggunakan 'nama_lengkap' pada parameter modal edit -->
                                    <button onclick="openEditModal('{{ $user['id_user'] ?? $user->id_user }}', '{{ $user['nama_lengkap'] ?? $user->nama_lengkap }}', '{{ $user['username'] ?? $user->username }}', '{{ $user['role'] ?? $user->role }}')" class="text-yellow-400 hover:text-yellow-300 text-xs font-medium bg-yellow-400/10 px-3 py-1.5 rounded-lg border border-yellow-400/20">
                                        Edit
                                    </button>

                                    <form action="{{ route('admin.user.destroy', $user['id_user'] ?? $user->id_user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
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

    <!-- Modal Edit User -->
    <div id="editModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">
        <div class="bg-slate-800 border border-slate-700 rounded-2xl w-full max-w-md p-6 shadow-2xl">
            <h3 class="text-lg font-bold text-white mb-4">Edit Data User</h3>
            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Nama Lengkap</label>
                    <input type="text" id="edit_nama" name="nama" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Username</label>
                    <input type="text" id="edit_username" name="username" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 uppercase mb-2">Role</label>
                    <select id="edit_role" name="role" class="w-full bg-slate-900/60 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500" required>
                        <option value="Admin">Admin</option>
                        <option value="Petugas">Petugas</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl text-gray-400 hover:bg-slate-700 text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, nama, username, role) {
            document.getElementById('editForm').action = '/admin/user/' + id;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_role').value = role;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>

</body>
</html>