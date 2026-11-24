<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PetugasDashboardController extends Controller
{
    public function index()
    {
        // Hitung total akumulasi data agar statistik langsung terisi angka
        $totalKendaraanHariIni = \App\Models\Transaksi::count();
        $kendaraanSedangParkir = \App\Models\Transaksi::where('status', 'masuk')->count();
        $kendaraanSudahKeluar = \App\Models\Transaksi::where('status', 'keluar')->count();
        
        $recentTransaksi = \App\Models\Transaksi::with('kendaraan')
            ->orderBy('id_parkir', 'desc')
            ->take(5)
            ->get();

        return view('petugas.dashboard', compact(
            'totalKendaraanHariIni',
            'kendaraanSedangParkir',
            'kendaraanSudahKeluar',
            'recentTransaksi'
        ));
    }
}