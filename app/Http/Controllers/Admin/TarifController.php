<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TarifController extends Controller
{
    // Private method untuk mengambil data default
    private function getDefaultTarif()
    {
        return [
            ['id_tarif' => 1, 'jenis_kendaraan' => 'Motor', 'tarif_per_jam' => 2000, 'tarif_maksimal' => 10000],
            ['id_tarif' => 2, 'jenis_kendaraan' => 'Mobil', 'tarif_per_jam' => 5000, 'tarif_maksimal' => 30000],
        ];
    }

    // Function Index
    public function index()
    {
        // Jika session belum ada, isi dulu dengan data default
        $tarif = session('tarif', $this->getDefaultTarif());

        return view('admin.tarif', compact('tarif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_kendaraan' => 'required',
            'tarif_per_jam'   => 'required',
            'tarif_maksimal'  => 'required',
        ]);

        $tarif = session('tarif', $this->getDefaultTarif());

        // Menghilangkan titik jika pengguna menginput menggunakan titik (contoh: 50.000 -> 50000)
        $tarifPerJam = (int) str_replace('.', '', $request->tarif_per_jam);
        $tarifMaksimal = (int) str_replace('.', '', $request->tarif_maksimal);

        $dataBaru = [
            'id_tarif'        => count($tarif) + 1,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'tarif_per_jam'   => $tarifPerJam,
            'tarif_maksimal'  => $tarifMaksimal,
        ];

        $tarif[] = $dataBaru;
        session(['tarif' => $tarif]);

        return redirect()->route('admin.tarif.index')->with('success', 'Tarif berhasil ditambahkan!');
    }
    // Function Update (Edit Data)
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_kendaraan' => 'required',
            'tarif_per_jam'   => 'required',
            'tarif_maksimal'  => 'required',
        ]);

        $tarif = session('tarif', $this->getDefaultTarif());

        foreach ($tarif as &$item) {
            if ($item['id_tarif'] == $id) {
                $item['jenis_kendaraan'] = $request->jenis_kendaraan;
                $item['tarif_per_jam']   = (int) str_replace('.', '', $request->tarif_per_jam);
                $item['tarif_maksimal']  = (int) str_replace('.', '', $request->tarif_maksimal);
                break;
            }
        }

        session(['tarif' => $tarif]);

        return redirect()->route('admin.tarif.index')->with('success', 'Tarif berhasil diperbarui!');
    }

    // Function Destroy (Hapus Data)
    public function destroy($id)
    {
        $tarif = session('tarif', $this->getDefaultTarif());

        $tarif = array_filter($tarif, function ($item) use ($id) {
            return $item['id_tarif'] != $id;
        });

        session(['tarif' => array_values($tarif)]);

        return redirect()->route('admin.tarif.index')->with('success', 'Tarif berhasil dihapus!');
    }
}