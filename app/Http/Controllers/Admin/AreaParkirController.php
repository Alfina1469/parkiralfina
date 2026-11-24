<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\AreaParkir;

class AreaParkirController extends Controller
{
    // 1. Menampilkan halaman Area Parkir & sinkronisasi otomatis jumlah terisi
    public function index()
    {
        $areas = AreaParkir::all();

        $areaMotor = AreaParkir::where('nama_area', 'LIKE', '%motor%')->first();
        $areaMobil = AreaParkir::where('nama_area', 'LIKE', '%mobil%')->first();
        // Kirim dengan beberapa variasi nama variabel agar cocok dengan file blade kamu

        if ($areaMotor) {
            DB::table('tb_transaksi')
                ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
                ->where('tb_transaksi.status', 'masuk')
                ->where('tb_kendaraan.jenis_kendaraan', 'LIKE', '%motor%')
                ->update(['tb_transaksi.id_area' => $areaMotor->id_area]);
        }

        if ($areaMobil) {
            DB::table('tb_transaksi')
                ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_kendaraan')
                ->where('tb_transaksi.status', 'masuk')
                ->where('tb_kendaraan.jenis_kendaraan', 'LIKE', '%mobil%')
                ->update(['tb_transaksi.id_area' => $areaMobil->id_area]);
        }

        foreach ($areas as $area) {
            $terisi = DB::table('tb_transaksi')
                ->where('id_area', $area->id_area)
                ->where('status', 'masuk')
                ->count();

            if ($area->terisi != $terisi) {
                $area->terisi = $terisi;
                $area->save();
            }
        }
        $areaParkir = $areas;
        $data = $areas;

        if (view()->exists('admin.area_parkir')) {
            return view('admin.area_parkir', compact('areas', 'areaParkir', 'data'));
        }

        return view('admin.area-parkir', compact('areas', 'areaParkir', 'data'));
    }

    // 2. Menyimpan area parkir baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_area' => 'required|string|max:100',
            'kapasitas' => 'required|integer|min:1',
        ]);

        AreaParkir::create([
            'nama_area' => $request->nama_area,
            'kapasitas' => $request->kapasitas,
            'terisi'    => $request->terisi ?? 0,
        ]);

        DB::table('tb_log_aktivitas')->insert([
            'id_user'         => Auth::id() ?? 1,
            'aktivitas'       => 'Menambahkan area parkir baru: ' . $request->nama_area,
            'waktu_aktivitas' => now(),
        ]);

        return redirect()->back()->with('success', 'Area parkir berhasil ditambahkan!');
    }

    // 3. Mengubah data area parkir (Edit)
    public function update(Request $request, $id)
    {
        $area = AreaParkir::findOrFail($id);

        $request->validate([
            'nama_area' => 'required|string|max:100',
            'kapasitas' => 'required|integer|min:1',
        ]);

        $area->update([
            'nama_area' => $request->nama_area,
            'kapasitas' => $request->kapasitas,
            'terisi'    => $request->terisi ?? $area->terisi,
        ]);

        DB::table('tb_log_aktivitas')->insert([
            'id_user'         => Auth::id() ?? 1,
            'aktivitas'       => 'Mengubah data area parkir: ' . $request->nama_area,
            'waktu_aktivitas' => now(),
        ]);

        return redirect()->back()->with('success', 'Data area parkir berhasil diperbarui!');
    }

    // 4. Menghapus area parkir
    public function destroy($id)
    {
        $area = AreaParkir::findOrFail($id);
        $namaArea = $area->nama_area;
        $area->delete();

        DB::table('tb_log_aktivitas')->insert([
            'id_user'         => Auth::id() ?? 1,
            'aktivitas'       => 'Menghapus area parkir: ' . $namaArea,
            'waktu_aktivitas' => now(),
        ]);

        return redirect()->back()->with('success', 'Area parkir berhasil dihapus!');
    }
}