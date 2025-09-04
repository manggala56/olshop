<?php

namespace App\Filament\Resources\DilaporkanResource\Pages;

use App\Filament\Resources\DilaporkanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDilaporkan extends EditRecord
{
    protected static string $resource = DilaporkanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
