<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenggajianPegawai extends Model
{
    use HasFactory;

    protected $table = 'penggajian_pegawais';
    protected $primaryKey = 'id_penggajian';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
    'id_pegawai',
    'tanggal_gaji',
    'periode_gaji',
    'gaji_pokok',
    'tunjangan',
    'potongan',
    'total_gaji',
    'metode_pembayaran',
    'status_pembayaran',
    'keterangan',
];

    public function pegawai(): BelongsTo
    {
        // Relasi ke model Pegawai menggunakan id_pegawai sebagai kuncinya
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }
}