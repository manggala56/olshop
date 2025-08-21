<?php

namespace App\Filament\Resources\ShopeeFinancialResource\Pages;

use App\Filament\Resources\ShopeeFinancialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShopeeFinancials extends ListRecords
{
    protected static string $resource = ShopeeFinancialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
