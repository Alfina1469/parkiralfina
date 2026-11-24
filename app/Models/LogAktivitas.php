<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'tb_log_aktivitas';

    // Primary key menggunakan underscore
    protected $primaryKey = 'id_log';

    public $timestamps = false;

    // Kolom yang diizinkan untuk diisi sesuai database HeidiSQL
    protected $fillable = [
        'id_user',
        'aktivitas',
        'waktu_aktivitas',
    ];

    /**
     * Relasi ke Model User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}