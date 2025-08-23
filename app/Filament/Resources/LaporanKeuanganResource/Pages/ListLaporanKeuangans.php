<?php

namespace App\Filament\Resources\LaporanKeuanganResource\Pages;

use App\Filament\Resources\LaporanKeuanganResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListLaporanKeuangans extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = LaporanKeuanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            // Actions\Action::make('Sync')
            // ->icon('heroicon-m-arrow-up-right'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            LaporanKeuanganResource\Widgets\FinancialSummary::class,
            LaporanKeuanganResource\Widgets\feebreakdown::class,
            LaporanKeuanganResource\Widgets\cartkeuangan::class
        ];
    }
}
