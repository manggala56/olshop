<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction_tokopedia extends Model
{
    use HasFactory;

    protected $table = 'transaction_tokopedias';

    protected $fillable = [
        'order_id',
        'type',
        'order_created_at',
        'order_settled_at',
        'currency',
        'total_settlement_amount',
        'total_revenue',
        'subtotal_after_discounts',
        'subtotal_before_discounts',
        'seller_discounts',
        'refund_subtotal_after_discounts',
        'refund_subtotal_before_discounts',
        'refund_of_seller_discounts',
        'total_fees',
        'tiktok_commission_fee',
        'flat_fee',
        'sales_fee',
        'pre_order_service_fee',
        'mall_service_fee',
        'payment_fee',
        'shipping_cost',
        'shipping_costs_passed_to_logistic',
        'replacement_shipping_fee',
        'exchange_shipping_fee',
        'shipping_cost_borne_by_platform',
        'shipping_cost_paid_by_customer',
        'refunded_shipping_paid_by_customer',
        'return_shipping_passed_to_customer',
        'shipping_cost_subsidy',
        'affiliate_commission',
        'dynamic_commission',
        'live_specials_fee',
        'voucher_xtra_fee',
        'eams_fee',
        'brand_flash_sale_fee',
        'bonus_cashback_fee',
        'dt_handling_fee',
        'paylater_handling_fee',
        'adjustment_amount',
        'related_order_id',
        'customer_payment',
        'customer_refund',
        'seller_co_funded_voucher',
        'refund_seller_co_funded_voucher',
        'platform_discounts',
        'refund_platform_discounts',
        'platform_co_funded_vouchers',
        'refund_platform_co_funded_vouchers',
        'seller_shipping_cost_discount',
        'estimated_weight_g',
        'actual_weight_g',
        'shopping_center_items',
        'order_source',
    ];

    protected $casts = [
        'order_created_at' => 'datetime',
        'order_settled_at' => 'datetime',
        'total_settlement_amount' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'subtotal_after_discounts' => 'decimal:2',
        'subtotal_before_discounts' => 'decimal:2',
        'seller_discounts' => 'decimal:2',
        'refund_subtotal_after_discounts' => 'decimal:2',
        'refund_subtotal_before_discounts' => 'decimal:2',
        'refund_of_seller_discounts' => 'decimal:2',
        'total_fees' => 'decimal:2',
        'tiktok_commission_fee' => 'decimal:2',
        'flat_fee' => 'decimal:2',
        'sales_fee' => 'decimal:2',
        'pre_order_service_fee' => 'decimal:2',
        'mall_service_fee' => 'decimal:2',
        'payment_fee' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'shipping_costs_passed_to_logistic' => 'decimal:2',
        'replacement_shipping_fee' => 'decimal:2',
        'exchange_shipping_fee' => 'decimal:2',
        'shipping_cost_borne_by_platform' => 'decimal:2',
        'shipping_cost_paid_by_customer' => 'decimal:2',
        'refunded_shipping_paid_by_customer' => 'decimal:2',
        'return_shipping_passed_to_customer' => 'decimal:2',
        'shipping_cost_subsidy' => 'decimal:2',
        'affiliate_commission' => 'decimal:2',
        'dynamic_commission' => 'decimal:2',
        'live_specials_fee' => 'decimal:2',
        'voucher_xtra_fee' => 'decimal:2',
        'eams_fee' => 'decimal:2',
        'brand_flash_sale_fee' => 'decimal:2',
        'bonus_cashback_fee' => 'decimal:2',
        'dt_handling_fee' => 'decimal:2',
        'paylater_handling_fee' => 'decimal:2',
        'adjustment_amount' => 'decimal:2',
        'customer_payment' => 'decimal:2',
        'customer_refund' => 'decimal:2',
        'seller_co_funded_voucher' => 'decimal:2',
        'refund_seller_co_funded_voucher' => 'decimal:2',
        'platform_discounts' => 'decimal:2',
        'refund_platform_discounts' => 'decimal:2',
        'platform_co_funded_vouchers' => 'decimal:2',
        'refund_platform_co_funded_vouchers' => 'decimal:2',
        'seller_shipping_cost_discount' => 'decimal:2',
        'estimated_weight_g' => 'integer',
        'actual_weight_g' => 'integer',
    ];

    // Optional: Jika Anda ingin scope untuk memfilter berdasarkan bulan atau rentang tanggal
    public function scopeInPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('order_settled_at', [$startDate, $endDate]);
    }
}
