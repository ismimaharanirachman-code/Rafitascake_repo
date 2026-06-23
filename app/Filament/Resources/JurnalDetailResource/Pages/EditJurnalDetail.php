<?php

namespace App\Filament\Resources\JurnalDetailResource\Pages;

use App\Filament\Resources\JurnalDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJurnalDetail extends EditRecord
{
    protected static string $resource = JurnalDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
