<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaksi; // Ganti sesuai nama model transaksimu

class LaporanController extends Controller
{
    public function index()
    {
        // Mengambil transaksi selesai yang diurutkan berdasarkan id_parkir terbaru beserta relasi kendaraannya
        $laporans = \App\Models\Transaksi::with('kendaraan')
                    ->where('status', 'keluar')
                    ->orderBy('id_parkir', 'desc')
                    ->get();

        // Hitung total pendapatan untuk ditampilkan di card rekap
        $totalPendapatan = $laporans->sum('biaya_total');
        $totalTransaksi = $laporans->count();

        return view('owner.laporan', compact('laporans', 'totalPendapatan', 'totalTransaksi'));
    }
}