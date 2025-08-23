<?php

namespace App\Filament\Resources\LaporanKeuanganResource\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use App\Filament\Resources\LaporanKeuanganResource\Pages\ListLaporanKeuangans;

class FeeBreakdown extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $heading = 'Breakdown Biaya';

    protected function getTablePage(): string
    {
        return ListLaporanKeuangans::class;
    }

    protected function getData(): array
    {
        $stats = $this->getPageTableQuery();

        // Hitung total masing-masing biaya
        $admin_fee = $stats->sum('admin_fee');
        $affiliate_fee = $stats->sum('affiliate_fee');
        $shipping_fee = $stats->sum('shipping_fee');
        $additional_fees = $stats->sum('additional_fees');
        $insurance_fees = $stats->sum('insurance_fees');
        $refund_fee = $stats->sum('refund_amount');

        return [
            'labels' => [
                'Admin Fee: Rp ' . number_format($admin_fee, 0, ',', '.'),
                'Affiliate Fee: Rp ' . number_format($affiliate_fee, 0, ',', '.'),
                'Shipping Fee: Rp ' . number_format($shipping_fee, 0, ',', '.'),
                'Biaya Tambahan: Rp ' . number_format($additional_fees, 0, ',', '.'),
                'Biaya Asuransi: Rp ' . number_format($insurance_fees, 0, ',', '.'),
                'Biaya Refund: Rp ' . number_format($refund_fee, 0, ',', '.')
            ],
            'datasets' => [
                [
                    'label' => 'Jumlah Biaya',
                    'data' => [
                        $admin_fee,
                        $affiliate_fee,
                        $shipping_fee,
                        $additional_fees,
                        $insurance_fees,
                        $refund_fee
                    ],
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ],
                    'hoverOffset' => 4,
                ]
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'font' => [
                            'size' => 12
                        ]
                    ]
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
