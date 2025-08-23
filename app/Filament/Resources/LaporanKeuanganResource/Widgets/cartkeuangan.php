<?php

namespace App\Filament\Resources\LaporanKeuanganResource\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use App\Filament\Resources\LaporanKeuanganResource\Pages\ListLaporanKeuangans;

class cartkeuangan extends ChartWidget
{
    use InteractsWithPageTable;
    protected function getTablePage(): string
    {
        return ListLaporanKeuangans::class;
    }
    protected static ?string $heading = 'Summary Penjualan';

    protected function getData(): array
    {
        $data = $this->getPageTableQuery();
        return [
            'labels' => ['Tokopedia', 'Shopee'],
            'datasets' => [
                [
                    'label' => 'Penjualan Bersih',
                    'data' => $data->pluck('net_sales')->toArray(),
                    'backgroundColor' => ['#4CAF50', '#2196F3'],
                ],
                [
                    'label' => 'Pendapatan Bersih',
                    'data' => $data->pluck('net_income')->toArray(),
                    'backgroundColor' => ['#8BC34A', '#03A9F4'],
                ],
            ],
            'labels' => $data->pluck('source')->map(fn($source) => ucfirst($source))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
