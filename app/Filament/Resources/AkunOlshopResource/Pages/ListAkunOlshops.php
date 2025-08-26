<?php

namespace App\Filament\Resources\AkunOlshopResource\Pages;

use App\Filament\Resources\AkunOlshopResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAkunOlshops extends ListRecords
{
    protected static string $resource = AkunOlshopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
