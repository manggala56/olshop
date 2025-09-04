<?php

namespace App\Filament\Resources\DilaporkanResource\Pages;

use App\Filament\Resources\DilaporkanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDilaporkans extends ListRecords
{
    protected static string $resource = DilaporkanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
