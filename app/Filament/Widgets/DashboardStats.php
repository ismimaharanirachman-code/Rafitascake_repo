<?php

namespace App\Filament\Widgets;

use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [

            Stat::make('Total Produk', Produk::count())
                ->description('Jumlah produk tersedia')
                ->icon('heroicon-o-gift')
                ->color('success')
                ->url('/admin/produks'),

            Stat::make('Total Pelanggan', Pelanggan::count())
                ->description('Pelanggan terdaftar')
                ->icon('heroicon-o-users')
                ->color('info')
                ->url('/admin/pelanggans'),

            Stat::make('Total Transaksi', Penjualan::count())
                ->description('Jumlah transaksi penjualan')
                ->icon('heroicon-o-shopping-cart')
                ->color('warning')
                ->url('/admin/penjualans'),

            Stat::make(
                'Total Penjualan',
                'Rp ' . number_format(
                    Penjualan::sum('total_harga'),
                    0,
                    ',',
                    '.'
                )
            )
                ->description('Total pendapatan penjualan')
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->url('/admin/penjualans'),

        ];
    }
}