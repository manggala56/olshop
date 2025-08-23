<?php

namespace App\Filament\Resources\LaporanPenjualanResource\Pages;

use App\Filament\Resources\LaporanPenjualanResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListLaporanPenjualans extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = LaporanPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];

    }
    protected function getHeaderWidgets(): array
    {
        return [
            // LaporanPenjualanResource\Widgets\FinancialOverview::class,
            LaporanPenjualanResource\Widgets\FinancialReport::class
        ];
    }
}
