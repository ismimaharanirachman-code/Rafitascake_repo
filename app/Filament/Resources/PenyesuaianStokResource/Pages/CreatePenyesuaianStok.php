<?php

namespace App\Filament\Resources\PenyesuaianStokResource\Pages;

use App\Filament\Resources\PenyesuaianStokResource;
use App\Models\Produk;
use Filament\Resources\Pages\CreateRecord;

class CreatePenyesuaianStok extends CreateRecord
{
    protected static string $resource = PenyesuaianStokResource::class;

    protected static ?string $title = 'Koreksi Penyesuaian Stok';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['dibuat_oleh'] = auth()->id();
        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        $produk = Produk::find($record->produk_id);

        if ($produk) {
            $produk->stok = $record->stok_sesudah;
            $produk->save();
        }
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Penyesuaian stok berhasil disimpan';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}