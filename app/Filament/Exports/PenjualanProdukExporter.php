<?php

namespace App\Filament\Exports;

use App\Models\PenjualanProduk;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PenjualanProdukExporter extends Exporter
{
    protected static ?string $model = PenjualanProduk::class;

    public static function getColumns(): array
    {
        return [

            ExportColumn::make('no_nota')
                ->label('No Nota'),

            ExportColumn::make('tanggal')
                ->label('Tanggal'),

            ExportColumn::make('status')
                ->label('Status'),

            ExportColumn::make('total_harga')
                ->label('Total Harga'),

            ExportColumn::make('metode_pembayaran')
                ->label('Metode Pembayaran'),

            ExportColumn::make('pelanggan.nama_pelanggan')
                ->label('Pelanggan'),

        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export berhasil.';

        return $body;
    }
}