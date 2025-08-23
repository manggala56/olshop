<?php

namespace App\Filament\Resources\LaporanKeuanganResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use App\Filament\Resources\LaporanKeuanganResource\Pages\ListLaporanKeuangans;

class FinancialSummary extends BaseWidget
{
    use InteractsWithPageTable;
    protected function getTablePage(): string
    {
        return ListLaporanKeuangans::class;
    }
    protected function getStats(): array
    {
        // data mapping
        $stats = $this->getPageTableQuery();

        return [
        Stat::make('Total Penjualan Bersih', 'Rp ' . number_format($stats->sum('net_sales'), 0, ',', '.'))
            ->description('Total penjualan setelah diskon dan refund')
            ->color('success'),

        Stat::make('Total Biaya Admin', 'Rp ' . number_format($stats->sum('admin_fee'), 0, ',', '.'))
            ->description('10% dari penjualan bersih')
            ->color('danger'),

        Stat::make('Total Biaya Afiliasi', 'Rp ' . number_format($stats->sum('affiliate_fee'), 0, ',', '.'))
            ->description('Hanya untuk Tokopedia')
            ->color('warning'),

        Stat::make('Pendapatan Bersih', 'Rp ' . number_format($stats->sum('net_income'), 0, ',', '.'))
            ->description('Setelah semua biaya')
            ->color('success')
            ->extraAttributes(['class' => 'font-bold']),
    ];
    }
}
