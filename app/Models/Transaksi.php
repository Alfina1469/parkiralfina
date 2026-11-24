<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'tb_transaksi';
    protected $primaryKey = 'id_parkir'; // Sesuai DB
    public $timestamps = false;

    protected $fillable = [
        'id_kendaraan',
        'waktu_masuk',
        'waktu_keluar',
        'id_tarif',
        'durasi_jam',
        'biaya_total',
        'status',
        'id_user',
        'id_area',
    ];

    // Relasi ke tabel kendaraan
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'id_kendaraan', 'id_kendaraan');
    }

    // Trigger otomatis saat kendaraan masuk & keluar (Eloquent Events)
    protected static function booted(): void
    {
        // Saat transaksi baru dibuat (status = masuk) -> slot terisi bertambah 1
        static::created(function ($transaksi) {
            if ($transaksi->status === 'masuk' && $transaksi->id_area) {
                AreaParkir::where('id_area', $transaksi->id_area)
                    ->increment('terisi');
            }
        });

        // Saat transaksi di-update (status berubah dari masuk -> keluar) -> slot terisi berkurang 1
        static::updated(function ($transaksi) {
            if (
                $transaksi->isDirty('status') &&
                $transaksi->getOriginal('status') === 'masuk' &&
                $transaksi->status === 'keluar' &&
                $transaksi->id_area
            ) {
                $area = AreaParkir::find($transaksi->id_area);
                if ($area && $area->terisi > 0) {
                    $area->decrement('terisi');
                }
            }
        });
    }
}
