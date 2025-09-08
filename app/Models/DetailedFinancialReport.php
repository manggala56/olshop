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
            ->leftJoin('akuns as a', 'a.name_akun', '=', 'fdt.store_name')
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
                DB::raw("'tokopedia' as source"),
                DB::raw('a.name_akun as store_name')
            );

        // Query untuk Shopee - pastikan urutan kolom sama dengan Tokopedia
        $shopeeQuery = DB::table('finacial_data_shopees as fds')
        // Tambahkan join ke tabel akuns untuk mendapatkan nama toko
        ->leftJoin('akuns as a', 'a.name_akun', '=', 'fds.store_name')
        ->select(
            // Mengubah 'id' menjadi 'fds.id' untuk mengatasi masalah "ambiguous column name: id"
            'fds.id',
            'fds.order_number as order_id',
            'fds.order_status',
            'fds.product_name',
            'fds.quantity',
            'fds.product_sku as sku',
            'fds.product_price as unit_price',
            'fds.total_product_price as subtotal_before_discount',
            'fds.total_discount as platform_discount',
            'fds.seller_discount',
            DB::raw('fds.total_product_price - fds.total_discount as subtotal_after_discount'),
            'fds.shopee_discount',
            'fds.shipping_cost_paid_by_buyer as shipping_fee',
            DB::raw('0 as original_shipping_fee'),
            DB::raw('0 as shipping_fee_seller_discount'),
            DB::raw('0 as shipping_fee_platform_discount'),
            DB::raw('0 as payment_platform_discount'),
            DB::raw('0 as buyer_service_fee'),
            DB::raw('0 as handling_fee'),
            DB::raw('0 as shipping_insurance'),
            DB::raw('0 as item_insurance'),
            'fds.total_payment as total_amount',
            DB::raw('0 as refund_amount'),
            'fds.total_payment as net_sales',
            DB::raw('(fds.total_payment * 0.1) as admin_fee'),
            DB::raw('0 as additional_fees'),
            DB::raw('0 as insurance_fees'),
            DB::raw('0 as affiliate_fee'),
            DB::raw('(fds.total_payment * 0.9) as net_income'),
            'fds.order_created_at as order_date',
            DB::raw("'shopee' as source"),
            DB::raw('a.name_akun as store_name') // Ambil nama akun sebagai nama toko
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
