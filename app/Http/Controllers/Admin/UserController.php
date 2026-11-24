<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan daftar user
     */
    public function index()
    {
        $users = User::all();
        return view('admin.user', compact('users'));
    }
    
    /**
     * Memproses tambah user baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required',
            'username' => 'required|unique:tb_user,username',
            'password' => 'required',
            'role'     => 'required',
        ]);

        // 1. Disesuaikan dengan nama kolom 'nama_lengkap' pada tb_user
        $user = User::create([
            'nama_lengkap' => $request->nama,
            'username'     => $request->username,
            'password'     => Hash::make($request->password),
            'role'         => strtolower($request->role),
            'status_aktif' => 1,
        ]);

        // 2. Disesuaikan dengan nama kolom 'id_user' pada tb_log_aktivitas
        LogAktivitas::create([
            'id_user'   => Auth::id(),
            'deskripsi' => 'Menambahkan user baru: ' . $request->nama,
            'waktu'     => now(),
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Memproses update data user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama'     => 'required',
            'username' => 'required|unique:tb_user,username,' . $id . ',id_user',
            'role'     => 'required',
        ]);

        // 1. Dipetakan ke 'nama_lengkap'
        $data = [
            'nama_lengkap' => $request->nama,
            'username'     => $request->username,
            'role'         => strtolower($request->role),
        ];

        // Update password jika diisi pada form modal edit
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // 2. Dipetakan ke 'id_user' pada tb_log_aktivitas
        LogAktivitas::create([
            'id_user'   => Auth::id(),
            'deskripsi' => 'Mengubah data user: ' . $request->nama_lengkap,
            'waktu'     => now(),
        ]);

        return redirect()->back()->with('success', 'Data user berhasil diperbarui!');
    }

    /**
     * Memproses hapus data user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $namaUser = $user->nama_lengkap ?? $user->username;

        $user->delete();

        // Dipetakan ke 'id_user' pada tb_log_aktivitas
        LogAktivitas::create([
            'id_user'   => Auth::id(),
            'deskripsi' => 'Menghapus user: ' . $namaUser,
            'waktu'     => now(),
        ]);

        return redirect()->back()->with('success', 'User berhasil dihapus!');
    }
}