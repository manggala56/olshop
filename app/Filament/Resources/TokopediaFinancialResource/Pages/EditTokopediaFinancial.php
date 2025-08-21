<?php

namespace App\Filament\Resources\TokopediaFinancialResource\Pages;

use App\Filament\Resources\TokopediaFinancialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTokopediaFinancial extends EditRecord
{
    protected static string $resource = TokopediaFinancialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
