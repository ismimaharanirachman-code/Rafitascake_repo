<?php

namespace App\Filament\Resources\PenggajianPegawaiResource\Pages;

use App\Filament\Resources\PenggajianPegawaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenggajianPegawais extends ListRecords
{
    protected static string $resource = PenggajianPegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
