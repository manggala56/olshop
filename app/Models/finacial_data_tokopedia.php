<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class finacial_data_tokopedia extends Model
{
    protected $table = 'finacial_data_tokopedias';
    protected $fillable = [
        'order_id',
        'order_status',
        'product_name',
        'quantity',
        'sku_category',
        'sku_unit_original_price',
        'sku_subtotal_before_discount',
        'sku_platform_discount',
        'sku_seller_discount',
        'sku_subtotal_after_discount',
        'shipping_fee_after_discount',
        'original_shipping_fee',
        'shipping_fee_seller_discount',
        'shipping_fee_platform_discount',
        'payment_platform_discount',
        'buyer_service_fee',
        'handling_fee',
        'shipping_insurance',
        'item_insurance',
        'order_amount',
        'order_refund_amount',
        'created_time',
        'store_name'
    ];
    protected $casts = [
        'created_time' => 'datetime',
    ];

}
