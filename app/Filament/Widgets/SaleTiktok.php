<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Transaction_tokopedia;
class SaleTiktok extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Settlement', 'Rp ' . number_format(Transaction_tokopedia::sum('total_settlement_amount'), 0, ',', '.')),
            Card::make('Total Revenue', 'Rp ' . number_format(Transaction_tokopedia::sum('total_revenue'), 0, ',', '.')),
            Card::make('Total Fees', 'Rp ' . number_format(Transaction_tokopedia::sum('total_fees'), 0, ',', '.')),
            Card::make('Total Shipping Cost', 'Rp ' . number_format(Transaction_tokopedia::sum('shipping_cost'), 0, ',', '.')),
            Card::make('Orders (TikTok)', Transaction_tokopedia::where('order_source', 'TikTok Shop')->count()),
            Card::make('Orders (Tokopedia)', Transaction_tokopedia::where('order_source', 'Tokopedia')->count()),
        ];
    }
}
