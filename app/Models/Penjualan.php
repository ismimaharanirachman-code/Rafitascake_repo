<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';
    protected $guarded = [];

    // 🔥 AUTO NO NOTA + DEFAULT PELANGGAN
    protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {

        // 🔥 AMBIL ID TERAKHIR
        $last = self::max('id') + 1;

        // 🔥 FORMAT INV001, INV002, dst
        $model->no_nota = 'INV' . str_pad($last, 3, '0', STR_PAD_LEFT);

        // pelanggan default
        if (!$model->pelanggan_id) {
            $model->pelanggan_id = 1;
        }
    });

    }
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id', 'id_pelanggan');
    }
    public function detail()
    {
        return $this->hasMany(PenjualanProduk::class, 'penjualan_id');
    }
    public function pengirimanEmail()
    {
        return $this->hasMany(PengirimanEmail::class);
    }
}