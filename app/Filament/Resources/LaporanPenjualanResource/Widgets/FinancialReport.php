<?php

namespace App\Filament\Resources\LaporanPenjualanResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\finacial_data_tokopedia;
use App\Models\financial_data_shopee;
use Illuminate\Support\Facades\DB;

class FinancialReport extends BaseWidget
{
    public ?array $filters = [];

    protected function getStats(): array
    {
        // Get date range from filters
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;
        $sku = $this->filters['sku'] ?? null;

        // Query for Tokopedia data with filters
        $tokopediaQuery = finacial_data_tokopedia::query();
        $shopeeQuery = financial_data_shopee::query();
        if ($startDate) {
            $tokopediaQuery->whereDate('created_time', '>=', $startDate);
            $shopeeQuery->whereDate('order_created_at', '>=', $startDate);
        }

        if ($endDate) {
            $tokopediaQuery->whereDate('created_time', '<=', $endDate);
            $shopeeQuery->whereDate('order_created_at', '<=', $endDate);
        }

        // Apply SKU filter
        if ($sku) {
            $tokopediaQuery->where('sku_category', $sku);
            $shopeeQuery->where('product_sku', $sku);
        }

        // Calculate total sales quantity
        $totalQuantity = $tokopediaQuery->sum('quantity') + $shopeeQuery->sum('quantity');

        // Calculate total income
        $tokopediaIncome = $tokopediaQuery->get()->sum(function ($item) {
            return $item->order_amount - $item->order_refund_amount;
        });

        $shopeeIncome = $shopeeQuery->get()->sum(function ($item) {
            return $item->total_payment;
        });

        $totalIncome = $tokopediaIncome + $shopeeIncome;

        // Calculate net income
        $tokopediaNetIncome = $tokopediaQuery->get()->sum(function ($item) {
            $baseAmount = $item->order_amount - $item->order_refund_amount;
            $adminFee = $baseAmount * 0.1; // 10% admin fee
            return $baseAmount - $adminFee;
        });

        $shopeeNetIncome = $shopeeQuery->get()->sum(function ($item) {
            $baseAmount = $item->total_payment;
            $adminFee = $baseAmount * 0.9; // 10% admin fee
            return $baseAmount - $adminFee;
        });

        $totalNetIncome = $tokopediaNetIncome + $shopeeNetIncome;

        return [
            Stat::make('Total Penjualan (pcs)', number_format($totalQuantity, 0, ',', '.'))
                ->description('Jumlah produk yang terjual')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalIncome, 0, ',', '.'))
                ->description('Total pendapatan kotor')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('info'),

            Stat::make('Pendapatan Bersih', 'Rp ' . number_format($totalNetIncome, 0, ',', '.'))
                ->description('Pendapatan setelah biaya admin')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
    protected function getFormSchema(): array
    {
        $tokopediaSkus = DB::table('finacial_data_tokopedias')
            ->distinct()
            ->pluck('sku_category', 'sku_category');

        $shopeeSkus = DB::table('finacial_data_shopees')
            ->distinct()
            ->pluck('product_sku', 'product_sku');

        $allSkus = $tokopediaSkus->union($shopeeSkus)->toArray();

        return [
            Forms\Components\Select::make('sku')
                ->label('Filter by SKU')
                ->options($allSkus)
                ->searchable(),
            Forms\Components\DatePicker::make('startDate')
                ->label('Dari Tanggal'),
            Forms\Components\DatePicker::make('endDate')
                ->label('Sampai Tanggal'),
        ];
    }
    protected function getFilters(): array
    {
        $tokopediaSkus = finacial_data_tokopedia::distinct()->pluck('sku_category', 'sku_category');
        $shopeeSkus = financial_data_shopee::distinct()->pluck('product_sku', 'product_sku');
        $allSkus = $tokopediaSkus->union($shopeeSkus)->toArray();

        return [
            'sku' => [
                'label' => 'Filter by SKU',
                'options' => $allSkus,
            ],
            'startDate' => [
                'label' => 'Dari Tanggal',
                'type' => 'date',
            ],
            'endDate' => [
                'label' => 'Sampai Tanggal',
                'type' => 'date',
            ],
        ];
    }
}
