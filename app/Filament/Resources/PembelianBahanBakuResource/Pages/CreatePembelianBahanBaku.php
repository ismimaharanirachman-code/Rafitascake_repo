<?php

namespace App\Filament\Resources\PembelianBahanBakuResource\Pages;

use App\Filament\Resources\PembelianBahanBakuResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePembelianBahanBaku extends CreateRecord
{
    protected static string $resource = PembelianBahanBakuResource::class;
    protected static bool $stockProcessed = false;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $total = 0;
        foreach ($data['detailPembelian'] ?? [] as &$detail) {
            $detail['harga'] = (int) str_replace(['Rp', '.', ' '], '', $detail['harga'] ?? 0);
            $detail['subtotal'] = (int) str_replace(['Rp', '.', ' '], '', $detail['subtotal'] ?? 0);
            $total += $detail['subtotal'];
        }
        $data['total'] = $total;
        return $data;
    }
}