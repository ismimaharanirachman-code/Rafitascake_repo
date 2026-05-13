<?php

namespace App\Filament\Resources\PenyesuaianStokResource\Pages;

use App\Filament\Resources\PenyesuaianStokResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenyesuaianStok extends ListRecords
{
    protected static string $resource = PenyesuaianStokResource::class;

    protected static ?string $title ='Penyesuaian Stok';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('+ Koreksi Penyesuaian Stok'),
        ];
    }
}