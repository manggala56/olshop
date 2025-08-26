<?php

namespace App\Filament\Resources\AkunOlshopResource\Pages;

use App\Filament\Resources\AkunOlshopResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAkunOlshop extends EditRecord
{
    protected static string $resource = AkunOlshopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
