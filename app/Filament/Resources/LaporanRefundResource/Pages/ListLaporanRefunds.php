<?php

namespace App\Filament\Resources\LaporanRefundResource\Pages;

use App\Filament\Resources\LaporanRefundResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaporanRefunds extends ListRecords
{
    protected static string $resource = LaporanRefundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
