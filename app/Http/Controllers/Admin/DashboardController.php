<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\AreaParkir;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Sinkronkan slot terisi di setiap area parkir dari tb_transaksi yang berstatus 'masuk'
        $areas = AreaParkir::all();
        foreach ($areas as $area) {
            $jumlahMasuk = DB::table('tb_transaksi')
                ->where('id_area', $area->id_area)
                ->where('status', 'masuk')
                ->count();

            if ($area->terisi != $jumlahMasuk) {
                $area->terisi = $jumlahMasuk;
                $area->save();
            }
        }

        // 2. Hitung total slot, terisi, dan tersedia secara real-time
        $totalSlot = (int) DB::table('tb_area_parkir')->sum('kapasitas');
        $slotTerisi = (int) DB::table('tb_transaksi')->where('status', 'masuk')->count();
        $slotTersedia = max(0, $totalSlot - $slotTerisi);
        $persenTerisi = $totalSlot > 0 ? round(($slotTerisi / $totalSlot) * 100, 1) : 0;

        // 3. Pendapatan hari ini
        $pendapatanHariIni = (int) DB::table('tb_transaksi')
            ->whereDate('waktu_keluar', today())
            ->where('status', 'keluar')
            ->sum('biaya_total');

        // 4. Aktivitas terkini dari model Transaksi (sesuai dengan admin/dashboard.blade.php)
        $aktivitasTerkini = Transaksi::with('kendaraan')
            ->orderBy('id_parkir', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSlot',
            'slotTerisi',
            'slotTersedia',
            'persenTerisi',
            'pendapatanHariIni',
            'aktivitasTerkini'
        ));
    }
}