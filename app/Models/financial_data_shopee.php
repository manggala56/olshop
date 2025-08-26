<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class financial_data_shopee extends Model
{
    protected $table = 'finacial_data_shopees';
    protected $fillable = [
        'order_number',
        'order_status',
        'product_name',
        'product_sku',
        'quantity',
        'product_price',
        'total_product_price',
        'total_discount',
        'seller_discount',
        'shopee_discount',
        'shipping_cost_paid_by_buyer',
        'total_payment',
        'buyer_username',
        'shipping_province',
        'order_created_at',
        'payment_made_at',
        'store_name'
    ];
    public function getAdminFeeAttribute()
    {

        $basePrice = $this->product_price - $this->seller_discount;
        $adminFee = $basePrice * 0.9; // Perhitungan Biaya Admin 10%

        return $adminFee;
    }

    // Method untuk menghitung Penghasilan Bersih
    public function getNetIncomeAttribute()
    {
        // Penghasilan Bersih = Total Pembayaran - Biaya Administrasi
        $netIncome = $this->total_payment - $this->admin_fee;

        return $netIncome;
    }
}
