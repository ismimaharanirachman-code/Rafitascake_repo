<?php

namespace App\Filament\Widgets;

use App\Models\Penjualan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PenjualanBulananChart extends ChartWidget
{
    protected static ?string $heading = 'Penjualan Per Bulan';

    protected function getData(): array
    {
        $data = Penjualan::select(
                DB::raw("MONTH(tanggal) as bulan"),
                DB::raw("SUM(total_harga) as total")
            )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $namaBulan = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $data->pluck('total')->toArray(),
                ],
            ],
            'labels' => $data->map(fn ($item) => $namaBulan[$item->bulan])->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}