<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';

    protected $guarded = [];
    public $timestamps = false;
    public function detail()
{
    return $this->hasMany(PenjualanProduk::class, 'produk_id');
}
}