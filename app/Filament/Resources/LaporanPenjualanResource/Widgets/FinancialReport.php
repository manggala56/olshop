<?php

namespace App\Filament\Resources\LaporanPenjualanResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\finacial_data_tokopedia;
use App\Models\financial_data_shopee;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Support\Facades\DB;
use App\Filament\Resources\LaporanPenjualanResource\Pages\ListLaporanPenjualans;

class FinancialReport extends BaseWidget
{
    public ?array $filters = [];
    use InteractsWithPageTable;
    protected function getTablePage(): string
    {
        return ListLaporanPenjualans::class;
    }

    protected function getStats(): array
    {

        $totalQuantity = $this->getPageTableQuery()->sum('quantity');
        $totalbelumdikirim = $this->getPageTableQuery()->where('status', 'Perlu Dikirim')->count();
        $totaldikirim = $this->getPageTableQuery()->where('status', 'Dikirim')->count();
        $totalselesai = $this->getPageTableQuery()->where('status', 'Selesai')->count();


        return [
            Stat::make('Total Penjualan (pcs)', number_format($totalQuantity, 0, ',', '.'))
                ->description('Jumlah produk yang terjual')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Belum Dikirim ' ,number_format($totalbelumdikirim, 0, ',', '.'))
                ->description('Total Barang Yang Belum Dikirim')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger'),
            Stat::make('Dikirim',number_format($totaldikirim, 0, ',', '.'))
                ->description('Total Barang Yang Dikirim')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('info'),
            Stat::make('Selesai ',number_format($totalselesai, 0, ',', '.'))
                ->description('Total Barang Yang Selesai')
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
