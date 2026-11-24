<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Slot & Kendaraan Aktif (Sama persis dengan Admin)
        $totalSlot = (int) DB::table('tb_area_parkir')->sum('kapasitas');
        $slotTerisi = (int) DB::table('tb_transaksi')->where('status', 'masuk')->count();
        $slotTersedia = max(0, $totalSlot - $slotTerisi);
        $persenTerisi = $totalSlot > 0 ? round(($slotTerisi / $totalSlot) * 100, 1) : 0;

        // 2. Pendapatan Hari Ini
        $pendapatanHariIni = (int) DB::table('tb_transaksi')
            ->whereDate('waktu_keluar', today())
            ->where('status', 'keluar')
            ->sum('biaya_total');

        // 3. Distribusi Jenis Kendaraan dari Database
        $jumlahMobil = DB::table('tb_transaksi')
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
            ->where('tb_kendaraan.jenis_kendaraan', 'LIKE', '%mobil%')
            ->count();

        $jumlahMotor = DB::table('tb_transaksi')
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
            ->where('tb_kendaraan.jenis_kendaraan', 'LIKE', '%motor%')
            ->count();

        // 4. 5 Aktivitas Transaksi Terbaru
        $aktivitasTerkini = Transaksi::with('kendaraan')
            ->orderBy('id_parkir', 'desc')
            ->take(5)
            ->get();

        return view('owner.dashboard', compact(
            'totalSlot',
            'slotTerisi',
            'slotTersedia',
            'persenTerisi',
            'pendapatanHariIni',
            'jumlahMobil',
            'jumlahMotor',
            'aktivitasTerkini'
        ));
    }
}