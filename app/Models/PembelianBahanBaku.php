<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianBahanBaku extends Model
{
    use HasFactory;

    protected $table = 'pembelian_bahan_baku';

    protected $fillable = [
        'kode_pembelian',
        'tanggal',
        'supplier_id',
        'total',
        'payment_method',
        'status_pembayaran',
    ];
    protected static function booted()
{
    static::creating(function ($model) {
        $last = self::latest()->first();
        if ($last && $last->kode_pembelian) {
            $number = (int) substr($last->kode_pembelian, 2) + 1;
        } else {
            $number = 1;
        }
        $model->kode_pembelian = 'PB' . str_pad($number, 3, '0', STR_PAD_LEFT);
    });
}

    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }

    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelian::class);
    }
}