<?php

namespace App\Filament\Resources\BebanOperasionalResource\Pages;

use App\Filament\Resources\BebanOperasionalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBebanOperasional extends EditRecord
{
    protected static string $resource = BebanOperasionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
<<<<<<< HEAD
=======
    protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
>>>>>>> 9caf477f02c014ae1bba8cd5471985347852ea7f
}
