<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengirimanEmail extends Model
{
    use HasFactory;

    protected $table = 'pengiriman_email';

    protected $fillable = [
        'penjualan_id',
        'status',
        'tgl_pengiriman_pesan',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }
}