<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\laporan_penjualan;

class Penjualan extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Penjualan', laporan_penjualan::sum('jumlah_laporan'))
                ->description('Total Penjualan')
                ->descriptionIcon('heroicon-o-arrow-up')
                ->color('success'),
            Stat::make('Total Penjualan', laporan_penjualan::sum('jumlah_laporan'))
                ->description('Total Penjualan')
                ->descriptionIcon('heroicon-o-arrow-up')
                ->color('success'),
            Stat::make('Total Penjualan', laporan_penjualan::sum('jumlah_laporan'))
                ->description('Total Penjualan')
                ->descriptionIcon('heroicon-o-arrow-up')
                ->color('success'),
        ];
    }
}
