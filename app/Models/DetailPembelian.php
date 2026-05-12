<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    use HasFactory;

    protected $table = 'detail_pembelian';

    protected $fillable = [
        'pembelian_bahan_baku_id',
        'bahan_baku_id',
        'qty',
        'harga',
        'subtotal',
    ];

    protected static function booted()
    {
        static::created(function ($detail) {

            $bahan = BahanBaku::find($detail->bahan_baku_id);

            if ($bahan) {

                $bahan->increment('stok', $detail->qty);
            }

            $total = DetailPembelian::where(
                'pembelian_bahan_baku_id',
                $detail->pembelian_bahan_baku_id
            )->sum('subtotal');

            PembelianBahanBaku::where(
                'id',
                $detail->pembelian_bahan_baku_id
            )->update([
                'total' => $total
            ]);
        });
    }

    public function pembelian()
    {
        return $this->belongsTo(
            PembelianBahanBaku::class,
            'pembelian_bahan_baku_id'
        );
    }

    public function bahanBaku()
    {
        return $this->belongsTo(
            BahanBaku::class,
            'bahan_baku_id'
        );
    }
} 