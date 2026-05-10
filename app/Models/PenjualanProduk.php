<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanProduk extends Model
{
    protected $table = 'penjualan_produk';

    protected $guarded = [];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}