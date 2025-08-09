<?php

namespace App\Filament\Resources\TransactionTokopediaResource\Pages;

use App\Filament\Resources\TransactionTokopediaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactionTokopedias extends ListRecords
{
    protected static string $resource = TransactionTokopediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
