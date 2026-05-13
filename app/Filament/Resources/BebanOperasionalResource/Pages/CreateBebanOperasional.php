<?php

namespace App\Filament\Resources\BebanOperasionalResource\Pages;

use App\Filament\Resources\BebanOperasionalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBebanOperasional extends CreateRecord
{
    protected static string $resource = BebanOperasionalResource::class;
    protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
}
