<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DetailedFinancialReport extends Model
{
    protected $table = 'detailed_financial_reports';
    public $timestamps = false;

    /**
     * Method untuk mendapatkan query detail laporan keuangan
     */
    public static function getDetailedReportQuery()
    {
        // Query untuk Tokopedia
        $tokopediaQuery = DB::table('finacial_data_tokopedias as fdt')
            ->leftJoin('transaction_tokopedias as tt', 'fdt.order_id', '=', 'tt.order_id')
            ->select(
                'fdt.id',
                'fdt.order_id',
                'fdt.order_status',
                'fdt.product_name',
                'fdt.quantity',
                'fdt.sku_category as sku',
                'fdt.sku_unit_original_price as unit_price',
                'fdt.sku_subtotal_before_discount as subtotal_before_discount',
                'fdt.sku_platform_discount as platform_discount',
                'fdt.sku_seller_discount as seller_discount',
                'fdt.sku_subtotal_after_discount as subtotal_after_discount',
                DB::raw('0 as shopee_discount'), // Kolom untuk Shopee discount (0 untuk Tokopedia)
                'fdt.shipping_fee_after_discount as shipping_fee',
                'fdt.original_shipping_fee',
                'fdt.shipping_fee_seller_discount',
                'fdt.shipping_fee_platform_discount',
                'fdt.payment_platform_discount',
                'fdt.buyer_service_fee',
                'fdt.handling_fee',
                'fdt.shipping_insurance',
                'fdt.item_insurance',
                'fdt.order_amount as total_amount',
                'fdt.order_refund_amount as refund_amount',
                DB::raw('fdt.order_amount - fdt.order_refund_amount as net_sales'),
                DB::raw('(fdt.order_amount - fdt.order_refund_amount) * 0.1 as admin_fee'),
                DB::raw('fdt.buyer_service_fee + fdt.handling_fee as additional_fees'),
                DB::raw('fdt.shipping_insurance + fdt.item_insurance as insurance_fees'),
                DB::raw('COALESCE(tt.affiliate_commission, 0) as affiliate_fee'),
                DB::raw('(fdt.order_amount - fdt.order_refund_amount) * 0.9 - COALESCE(tt.affiliate_commission, 0) as net_income'),
                'fdt.created_time as order_date',
                DB::raw("'tokopedia' as source")
            );

        // Query untuk Shopee - pastikan urutan kolom sama dengan Tokopedia
        $shopeeQuery = DB::table('finacial_data_shopees')
            ->select(
                'id',
                'order_number as order_id',
                'order_status',
                'product_name',
                'quantity',
                'product_sku as sku',
                'product_price as unit_price',
                'total_product_price as subtotal_before_discount',
                'total_discount as platform_discount', // Sesuaikan urutan
                'seller_discount',
                DB::raw('total_product_price - total_discount as subtotal_after_discount'),
                'shopee_discount', // Kolom shopee_discount di posisi yang sama
                'shipping_cost_paid_by_buyer as shipping_fee',
                DB::raw('0 as original_shipping_fee'),
                DB::raw('0 as shipping_fee_seller_discount'),
                DB::raw('0 as shipping_fee_platform_discount'),
                DB::raw('0 as payment_platform_discount'),
                DB::raw('0 as buyer_service_fee'),
                DB::raw('0 as handling_fee'),
                DB::raw('0 as shipping_insurance'),
                DB::raw('0 as item_insurance'),
                'total_payment as total_amount',
                DB::raw('0 as refund_amount'),
                'total_payment as net_sales',
                DB::raw('(total_payment * 0.1) as admin_fee'),
                DB::raw('0 as additional_fees'),
                DB::raw('0 as insurance_fees'),
                DB::raw('0 as affiliate_fee'),
                DB::raw('(total_payment * 0.9) as net_income'),
                'order_created_at as order_date',
                DB::raw("'shopee' as source")
            );

        return $tokopediaQuery->unionAll($shopeeQuery);
    }

    /**
     * Scope untuk filter by tanggal
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('order_date', [$startDate, $endDate]);
    }

    /**
     * Scope untuk filter by platform
     */
    public function scopePlatform($query, $platform)
    {
        if ($platform) {
            return $query->where('source', $platform);
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
}
