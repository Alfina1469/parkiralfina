<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas; // Jangan lupa import model LogAktivitas
use Illuminate\Support\Facades\Auth;

class LogAktivitasController extends Controller
{
    public function index()
    {
        // Mengambil data log dari database beserta relasi data user-nya
        $logAktivitas = LogAktivitas::orderBy('id_log', 'asc')->get();
        return view('admin.log_aktivitas', compact('logAktivitas'));
    }
}