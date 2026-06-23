<?php

namespace App\Filament\Widgets;

use App\Models\Penjualan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PenjualanPelangganChart extends ChartWidget
{
    protected static ?string $heading = 'Total Penjualan Per Pelanggan';

    protected function getData(): array
    {
        $data = Penjualan::join(
                'pelanggan',
                'penjualan.pelanggan_id',
                '=',
                'pelanggan.id_pelanggan'
            )
            ->select(
                'pelanggan.nama_pelanggan',
                DB::raw('SUM(penjualan.total_harga) as total_penjualan')
            )
            ->groupBy('pelanggan.nama_pelanggan')
            ->orderByDesc('total_penjualan')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $data->pluck('total_penjualan')->toArray(),
                ],
            ],
            'labels' => $data->pluck('nama_pelanggan')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}