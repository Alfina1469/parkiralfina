<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Transaksi;
use App\Models\Kendaraan;

class TransaksiController extends Controller
{
    // 1. Menampilkan halaman kelola transaksi
    public function index()
    {
        $transaksi = Transaksi::with('kendaraan')
            ->orderBy('id_parkir', 'desc')
            ->get();

        return view('petugas.transaksi', compact('transaksi'));
    }

    // 2. Menyimpan data kendaraan masuk (Otomatis pilih Zona Mobil / Motor)
    public function store(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string',
        ]);

        $platNomor = strtoupper(trim($request->plat_nomor));

        // Ambil input jenis dari form (bisa berupa id_tarif, id_jenis, atau teks 'motor'/'mobil')
        $inputJenis = $request->input('id_tarif')
                   ?? $request->input('id_jenis')
                   ?? $request->input('jenis_kendaraan');

        if (is_numeric($inputJenis)) {
            $tarif = DB::table('tb_tarif')->where('id_tarif', $inputJenis)->first();
        } else {
            $tarif = DB::table('tb_tarif')
                ->where('jenis_kendaraan', 'LIKE', '%' . $inputJenis . '%')
                ->first();
        }
        $tarif = $tarif ?? DB::table('tb_tarif')->first();

        // Tentukan teks jenis kendaraan ('Motor' atau 'Mobil')
        $jenisKendaraan = $tarif->jenis_kendaraan ?? (is_string($inputJenis) ? ucfirst(strtolower($inputJenis)) : 'Mobil');

        // Simpan atau cari kendaraan di tb_kendaraan
        $kendaraan = Kendaraan::where('plat_nomor', $platNomor)->first();
        if (!$kendaraan) {
            $kendaraan = new Kendaraan();
            $kendaraan->plat_nomor = $platNomor;
            if (Schema::hasColumn('tb_kendaraan', 'id_jenis') && is_numeric($inputJenis)) {
                $kendaraan->id_jenis = $inputJenis;
            }
            if (Schema::hasColumn('tb_kendaraan', 'id_tarif')) {
                $kendaraan->id_tarif = $tarif->id_tarif ?? 1;
            }
            if (Schema::hasColumn('tb_kendaraan', 'jenis_kendaraan')) {
                $kendaraan->jenis_kendaraan = $jenisKendaraan;
            }
            if (Schema::hasColumn('tb_kendaraan', 'warna')) {
                $kendaraan->warna = $request->input('warna') ?? '-';
            }
            if (Schema::hasColumn('tb_kendaraan', 'pemilik')) {
                $kendaraan->pemilik = $request->input('pemilik') ?? 'Umum';
            }
            $kendaraan->save();
        }

        // Cek apakah kendaraan masih parkir (status 'masuk')
        $masihParkir = Transaksi::where('id_kendaraan', $kendaraan->id_kendaraan)
            ->where('status', 'masuk')
            ->first();

        if ($masihParkir) {
            return redirect()->back()->with('error', 'Kendaraan dengan plat nomor ' . $platNomor . ' masih parkir di dalam!');
        }

        // Gunakan jenis kendaraan dari database jika sudah ada, atau dari pilihan form
        $jenisFinal = $kendaraan->jenis_kendaraan ?? $jenisKendaraan;

        // Cari area parkir yang namanya mengandung kata "Motor" atau "Mobil"
        $area = DB::table('tb_area_parkir')
            ->where('nama_area', 'LIKE', '%' . $jenisFinal . '%')
            ->first()
            ?? DB::table('tb_area_parkir')->first();

        // Buat transaksi masuk baru dengan id_area yang sudah sesuai jenis kendaraan
        $idParkirBaru = DB::table('tb_transaksi')->insertGetId([
            'id_kendaraan' => $kendaraan->id_kendaraan,
            'waktu_masuk'  => now(),
            'id_tarif'     => $kendaraan->id_tarif ?? $tarif->id_tarif ?? 1,
            'id_area'      => $area->id_area ?? 1,
            'id_user'      => Auth::id() ?? 1,
            'status'       => 'masuk',
        ]);

        // Catat otomatis ke tb_log_aktivitas
        DB::table('tb_log_aktivitas')->insert([
            'id_user'         => Auth::id() ?? 1,
            'aktivitas'       => 'Mencetak tiket parkir masuk plat ' . $platNomor . ' (' . ($area->nama_area ?? 'Area Utama') . ')',
            'waktu_aktivitas' => now(),
        ]);

        return redirect()->back()->with([
            'success'  => 'Kendaraan ' . $platNomor . ' masuk ke ' . ($area->nama_area ?? 'Area Parkir') . '!',
            'print_id' => $idParkirBaru,
        ]);
    }

    // 3. Memproses kendaraan keluar & menghitung biaya parkir
    public function prosesKeluar($id)
    {
        $transaksi = Transaksi::with('kendaraan')->findOrFail($id);

        $waktuMasuk = \Carbon\Carbon::parse($transaksi->waktu_masuk);
        $waktuKeluar = now();

        // Hitung durasi jam (minimal 1 jam)
        $durasiJam = ceil($waktuMasuk->diffInMinutes($waktuKeluar) / 60);
        if ($durasiJam < 1) {
            $durasiJam = 1;
        }

        // Ambil tarif per jam dari tb_tarif (default Rp 2.000)
        $tarif = DB::table('tb_tarif')->where('id_tarif', $transaksi->id_tarif)->first();
        $tarifPerJam = $tarif->tarif_per_jam ?? $tarif->harga_per_jam ?? 2000;
        $biayaTotal = $durasiJam * $tarifPerJam;

        $transaksi->update([
            'waktu_keluar' => $waktuKeluar,
            'durasi_jam'   => $durasiJam,
            'biaya_total'  => $biayaTotal,
            'status'       => 'keluar',
        ]);

        // Catat otomatis ke tb_log_aktivitas
        $platNomor = $transaksi->kendaraan->plat_nomor ?? '-';
        DB::table('tb_log_aktivitas')->insert([
            'id_user'         => Auth::id() ?? 1,
            'aktivitas'       => 'Memproses kendaraan keluar plat ' . $platNomor,
            'waktu_aktivitas' => now(),
        ]);

        return redirect()->back()->with('success', 'Kendaraan keluar! Total Biaya: Rp ' . number_format($biayaTotal, 0, ',', '.'));
    }

    // 4. Menampilkan halaman cetak struk / tiket parkir
    public function cetakStruk($id)
    {
        $transaksi = Transaksi::with('kendaraan')->findOrFail($id);

        return view('petugas.cetak_struk', compact('transaksi'));
    }
}