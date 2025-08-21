<?php

namespace App\Filament\Resources\TokopediaFinancialResource\Pages;

use App\Filament\Resources\TokopediaFinancialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTokopediaFinancials extends ListRecords
{
    protected static string $resource = TokopediaFinancialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
