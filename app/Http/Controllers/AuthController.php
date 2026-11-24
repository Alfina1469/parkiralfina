<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses autentikasi login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Redirect otomatis sesuai role user
            $role = strtolower($user->role);

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'petugas') {
                return redirect()->route('petugas.transaksi.index');
            } elseif ($role === 'owner') {
                return redirect()->route('owner.dashboard');
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah!',
        ]);
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}