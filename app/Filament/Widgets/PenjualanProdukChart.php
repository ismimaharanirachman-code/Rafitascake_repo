<?php

namespace App\Filament\Widgets;

use App\Models\PenjualanProduk;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PenjualanProdukChart extends ChartWidget
{
    protected static ?string $heading = 'Persentase Penjualan Tiap Produk';

    protected function getData(): array
    {
        $data = PenjualanProduk::join('produk', 'penjualan_produk.produk_id', '=', 'produk.id')
            ->select(
                'produk.nama_kue',
                DB::raw('SUM(penjualan_produk.qty) as total_qty')
            )
            ->groupBy('produk.nama_kue')
            ->get();

        return [
            'datasets' => [
                [
                    'data' => $data->pluck('total_qty')->toArray(),
                ],
            ],
            'labels' => $data->pluck('nama_kue')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}