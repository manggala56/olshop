<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CombinedFinancialData extends Model
{
    protected $table = 'combined_financial_data';
    public $timestamps = false;

    /**
     * Method untuk mendapatkan query builder dengan data gabungan
     */
    public static function getCombinedQuery()
    {
        $tokopediaQuery = DB::table('finacial_data_tokopedias')
            ->select(
                'id',
                'order_id',
                'order_status as status',
                'product_name',
                'quantity',
                'sku_category as sku',
                DB::raw('(order_amount - order_refund_amount) as total_amount'),
                DB::raw('((order_amount - order_refund_amount) * 0.9) as net_income'),
                'created_time as order_date',
                DB::raw("'tokopedia' as source")
            );

        $shopeeQuery = DB::table('finacial_data_shopees')
            ->select(
                'id',
                DB::raw('order_number as order_id'),
                'order_status as status',
                'product_name',
                'quantity',
                DB::raw('product_sku as sku'),
                DB::raw('total_payment as total_amount'),
                DB::raw('(total_payment * 0.9) as net_income'),
                DB::raw('order_created_at as order_date'),
                DB::raw("'shopee' as source")
            );

        return $tokopediaQuery->unionAll($shopeeQuery);
    }

    /**
     * Scope untuk filter by source
     */
    public function scopeSource($query, $source)
    {
        if ($source) {
            return $query->where('source', $source);
        }
        return $query;
    }

    /**
     * Scope untuk filter by SKU
     */
    public function scopeSku($query, $sku)
    {
        if ($sku) {
            return $query->where('sku', $sku);
        }
        return $query;
    }

    /**
     * Scope untuk filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        if ($startDate) {
            $query->whereDate('order_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('order_date', '<=', $endDate);
        }
        return $query;
    }
}
