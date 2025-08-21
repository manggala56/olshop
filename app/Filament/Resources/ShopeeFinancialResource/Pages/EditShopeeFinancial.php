<?php

namespace App\Filament\Resources\ShopeeFinancialResource\Pages;

use App\Filament\Resources\ShopeeFinancialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShopeeFinancial extends EditRecord
{
    protected static string $resource = ShopeeFinancialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
