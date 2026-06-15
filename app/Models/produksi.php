<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    use HasFactory;

    protected $table = 'produksi'; // Sesuaikan jika nama tabelmu 'produksi'

    protected $fillable = [
        'nama_produk',
        'qty_produksi',
        'tanggal_produksi',
    ];

    // INI BAGIAN YANG WAJIB ADA:
    protected $casts = [
        'tanggal_produksi' => 'date', // Mengubah string dari DB menjadi objek Carbon
    ];

    public function details()
    {
        return $this->hasMany(ProduksiDetail::class);
    }
}