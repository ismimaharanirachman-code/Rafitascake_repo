<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;


    protected $table = 'bahan_bakus';

   
    protected $fillable = [
        'nama_bahan',
        'stok',
        'satuan',
        'harga',
    ];

   
    protected $casts = [
        'stok' => 'integer',
        'harga' => 'integer',
    ];
}