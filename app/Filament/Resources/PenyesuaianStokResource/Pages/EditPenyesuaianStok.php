<?php

namespace App\Filament\Resources\PenyesuaianStokResource\Pages;

use App\Filament\Resources\PenyesuaianStokResource;
use App\Models\Produk;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenyesuaianStok extends EditRecord
{
    protected static string $resource = PenyesuaianStokResource::class;

    protected static ?string $title = 'Ubah Penyesuaian Stok';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Hapus'),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        $produk = Produk::find($record->produk_id);

        if ($produk) {
            $produk->stok = $record->stok_sesudah;
            $produk->save();
        }
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Penyesuaian stok berhasil diperbarui';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}