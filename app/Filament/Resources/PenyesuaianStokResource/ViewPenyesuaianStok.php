<?php

namespace App\Filament\Resources\PenyesuaianStokResource\Pages;

use App\Filament\Resources\PenyesuaianStokResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;

class ViewPenyesuaianStok extends ViewRecord
{
    protected static string $resource = PenyesuaianStokResource::class;

    protected static ?string $title = 'Detail Koreksi Stok';

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        // ✅ Ambil record di sini, bukan di dalam schema
        $record = $this->record;
        $produk = $this->record->produk;

        return $infolist
            ->schema([

                Section::make('Informasi Transaksi')
                    ->schema([
                        TextEntry::make('nomor_referensi')
                            ->label('No. Referensi')
                            ->weight('bold')
                            ->copyable(),

                        TextEntry::make('tipe')
                            ->label('Tipe')
                            ->badge()
                            ->formatStateUsing(fn () => '✏ Koreksi Stok')
                            ->color('warning'),

                        TextEntry::make('produk.nama_kue')
                            ->label('Produk'),

                        TextEntry::make('created_at')
                            ->label('Tanggal')
                            ->dateTime('d F Y, H:i'),

                        TextEntry::make('pembuat.name')
                            ->label('Diinput Oleh')
                            ->default('Sistem'),

                        TextEntry::make('keterangan')
                            ->label('Alasan Koreksi')
                            ->default('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Kartu Stok')
                    ->description('Laporan pergerakan stok — ' . $produk->nama_kue)
                    ->schema([
                        // ✅ viewData pakai array biasa, bukan fn()
                        ViewEntry::make('kartu_stok')
                            ->label('')
                            ->view('filament.resources.penyesuaian-stok.kartu-stok')
                            ->viewData([
                                'record' => $record,
                                'produk' => $produk,
                            ])
                            ->columnSpanFull(),
                    ]),

            ]);
    }
}