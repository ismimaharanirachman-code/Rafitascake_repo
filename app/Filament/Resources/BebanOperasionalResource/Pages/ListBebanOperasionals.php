<?php

namespace App\Filament\Resources\BebanOperasionalResource\Pages;

use App\Filament\Resources\BebanOperasionalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBebanOperasionals extends ListRecords
{
    protected static string $resource = BebanOperasionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
