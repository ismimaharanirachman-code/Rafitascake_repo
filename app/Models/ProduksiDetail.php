<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduksiDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'produksi_id',
        'bahan_baku_id',
        'jumlah_pakai',
        'harga_satuan', 
        'subtotal',
    ];

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class);
    }

    public function produksi()
    {
        return $this->belongsTo(Produksi::class);
    }
}