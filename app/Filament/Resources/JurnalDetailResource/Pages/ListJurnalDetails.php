<?php

namespace App\Filament\Resources\JurnalDetailResource\Pages;

use App\Filament\Resources\JurnalDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJurnalDetails extends ListRecords
{
    protected static string $resource = JurnalDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
