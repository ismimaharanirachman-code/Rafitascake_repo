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
        'expired_date',
        'storage_location',
    ];

<<<<<<< Updated upstream
=======
    public function produksiDetails()
    {
    return $this->hasMany(ProduksiDetail::class);
    }

>>>>>>> Stashed changes
    protected $casts = [
        'stok' => 'integer',
        'harga' => 'integer',
        'expired_date' => 'date',
    ];

}