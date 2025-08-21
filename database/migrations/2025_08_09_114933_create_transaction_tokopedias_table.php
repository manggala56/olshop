<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_tokopedias', function (Blueprint $table) {
            $table->id(); // Tetap sebagai primary key
            $table->string('order_id')->unique();
            $table->string('type')->nullable();
            $table->string('order_created_at')->nullable(); // Diubah menjadi string
            $table->string('order_settled_at')->nullable(); // Diubah menjadi string
            $table->string('currency')->nullable();
            $table->string('total_settlement_amount')->nullable(); // Diubah menjadi string
            $table->string('total_revenue')->nullable(); // Diubah menjadi string
            $table->string('subtotal_after_discounts')->nullable(); // Diubah menjadi string
            $table->string('subtotal_before_discounts')->nullable(); // Diubah menjadi string
            $table->string('seller_discounts')->nullable(); // Diubah menjadi string
            $table->string('refund_subtotal_after_discounts')->nullable(); // Diubah menjadi string
            $table->string('refund_subtotal_before_discounts')->nullable(); // Diubah menjadi string
            $table->string('refund_of_seller_discounts')->nullable(); // Diubah menjadi string
            $table->string('total_fees')->nullable(); // Diubah menjadi string
            $table->string('tiktok_commission_fee')->nullable(); // Diubah menjadi string
            $table->string('flat_fee')->nullable(); // Diubah menjadi string
            $table->string('sales_fee')->nullable(); // Diubah menjadi string
            $table->string('pre_order_service_fee')->nullable(); // Diubah menjadi string
            $table->string('mall_service_fee')->nullable(); // Diubah menjadi string
            $table->string('payment_fee')->nullable(); // Diubah menjadi string
            $table->string('shipping_cost')->nullable(); // Diubah menjadi string
            $table->string('shipping_costs_passed_to_logistic')->nullable(); // Diubah menjadi string
            $table->string('replacement_shipping_fee')->nullable(); // Diubah menjadi string
            $table->string('exchange_shipping_fee')->nullable(); // Diubah menjadi string
            $table->string('shipping_cost_borne_by_platform')->nullable(); // Diubah menjadi string
            $table->string('shipping_cost_paid_by_customer')->nullable(); // Diubah menjadi string
            $table->string('refunded_shipping_paid_by_customer')->nullable(); // Diubah menjadi string
            $table->string('return_shipping_passed_to_customer')->nullable(); // Diubah menjadi string
            $table->string('shipping_cost_subsidy')->nullable(); // Diubah menjadi string
            $table->string('affiliate_commission')->nullable(); // Diubah menjadi string
            $table->string('dynamic_commission')->nullable(); // Diubah menjadi string
            $table->string('live_specials_fee')->nullable(); // Diubah menjadi string
            $table->string('voucher_xtra_fee')->nullable(); // Diubah menjadi string
            $table->string('eams_fee')->nullable(); // Diubah menjadi string
            $table->string('brand_flash_sale_fee')->nullable(); // Diubah menjadi string
            $table->string('bonus_cashback_fee')->nullable(); // Diubah menjadi string
            $table->string('dt_handling_fee')->nullable(); // Diubah menjadi string
            $table->string('paylater_handling_fee')->nullable(); // Diubah menjadi string
            $table->string('adjustment_amount')->nullable(); // Diubah menjadi string
            $table->string('related_order_id')->nullable();
            $table->string('customer_payment')->nullable(); // Diubah menjadi string
            $table->string('customer_refund')->nullable(); // Diubah menjadi string
            $table->string('seller_co_funded_voucher')->nullable(); // Diubah menjadi string
            $table->string('refund_seller_co_funded_voucher')->nullable(); // Diubah menjadi string
            $table->string('platform_discounts')->nullable(); // Diubah menjadi string
            $table->string('refund_platform_discounts')->nullable(); // Diubah menjadi string
            $table->string('platform_co_funded_vouchers')->nullable(); // Diubah menjadi string
            $table->string('refund_platform_co_funded_vouchers')->nullable(); // Diubah menjadi string
            $table->string('seller_shipping_cost_discount')->nullable(); // Diubah menjadi string
            $table->string('estimated_weight_g')->nullable(); // Diubah menjadi string
            $table->string('actual_weight_g')->nullable(); // Diubah menjadi string
            $table->text('shopping_center_items')->nullable(); // Tetap text karena bisa panjang
            $table->string('order_source')->nullable();
            $table->timestamps(); // Tetap sebagai timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_tokopedias');
    }
};
