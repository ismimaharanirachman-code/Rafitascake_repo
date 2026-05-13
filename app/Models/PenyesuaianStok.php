<?php
// app/Models/PenyesuaianStok.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenyesuaianStok extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penyesuaian_stok';

    protected $fillable = [
        'produk_id',
        'tipe',
        'jumlah',
        'stok_sebelum',
        'stok_sesudah',
        'keterangan',
        'nomor_referensi',
        'dibuat_oleh',
    ];

    protected $casts = [
        'jumlah'       => 'integer',
        'stok_sebelum' => 'integer',
        'stok_sesudah' => 'integer',
    ];

    // ─── Relasi ──────────────────────────────────────────────
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public static function generateNomorReferensi(): string
    {
        $prefix = 'PS-' . date('Ymd') . '-';
        $urutan = self::withTrashed()
                      ->where('nomor_referensi', 'like', $prefix . '%')
                      ->count();
        return $prefix . str_pad($urutan + 1, 4, '0', STR_PAD_LEFT);
    }
}