<?php

namespace App\Filament\Resources\ProduksiResource\Pages;

use App\Filament\Resources\ProduksiResource;
use App\Models\BahanBaku;
use Filament\Resources\Pages\CreateRecord;

class CreateProduksi extends CreateRecord
{
    protected static string $resource = ProduksiResource::class;

    protected function afterCreate(): void
    {
        foreach ($this->record->details as $detail) {
            $bahan = BahanBaku::find($detail->bahan_baku_id);

            if ($bahan) {
                $bahan->stok -= $detail->jumlah_pakai;
                $bahan->save();
            }
        }
    }
}