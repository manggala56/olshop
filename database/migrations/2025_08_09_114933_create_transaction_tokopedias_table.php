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
            $table->id();
            $table->string('order_id')->unique();
            $table->string('type');
            $table->timestamp('order_created_at')->nullable();
            $table->timestamp('order_settled_at')->nullable();
            $table->string('currency');
            $table->decimal('total_settlement_amount', 15, 2);
            $table->decimal('total_revenue', 15, 2);
            $table->decimal('subtotal_after_discounts', 15, 2);
            $table->decimal('subtotal_before_discounts', 15, 2);
            $table->decimal('seller_discounts', 15, 2)->default(0);
            $table->decimal('refund_subtotal_after_discounts', 15, 2)->default(0);
            $table->decimal('refund_subtotal_before_discounts', 15, 2)->default(0);
            $table->decimal('refund_of_seller_discounts', 15, 2)->default(0);
            $table->decimal('total_fees', 15, 2);
            $table->decimal('tiktok_commission_fee', 15, 2);
            $table->decimal('flat_fee', 15, 2)->default(0);
            $table->decimal('sales_fee', 15, 2)->default(0);
            $table->decimal('pre_order_service_fee', 15, 2)->default(0);
            $table->decimal('mall_service_fee', 15, 2)->default(0);
            $table->decimal('payment_fee', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2);
            $table->decimal('shipping_costs_passed_to_logistic', 15, 2)->default(0);
            $table->decimal('replacement_shipping_fee', 15, 2)->default(0);
            $table->decimal('exchange_shipping_fee', 15, 2)->default(0);
            $table->decimal('shipping_cost_borne_by_platform', 15, 2)->default(0);
            $table->decimal('shipping_cost_paid_by_customer', 15, 2)->default(0);
            $table->decimal('refunded_shipping_paid_by_customer', 15, 2)->default(0);
            $table->decimal('return_shipping_passed_to_customer', 15, 2)->default(0);
            $table->decimal('shipping_cost_subsidy', 15, 2)->default(0);
            $table->decimal('affiliate_commission', 15, 2)->default(0);
            $table->decimal('dynamic_commission', 15, 2)->default(0);
            $table->decimal('live_specials_fee', 15, 2)->default(0);
            $table->decimal('voucher_xtra_fee', 15, 2)->default(0);
            $table->decimal('eams_fee', 15, 2)->default(0);
            $table->decimal('brand_flash_sale_fee', 15, 2)->default(0);
            $table->decimal('bonus_cashback_fee', 15, 2)->default(0);
            $table->decimal('dt_handling_fee', 15, 2)->default(0);
            $table->decimal('paylater_handling_fee', 15, 2)->default(0);
            $table->decimal('adjustment_amount', 15, 2)->default(0);
            $table->string('related_order_id')->nullable();
            $table->decimal('customer_payment', 15, 2);
            $table->decimal('customer_refund', 15, 2)->default(0);
            $table->decimal('seller_co_funded_voucher', 15, 2)->default(0);
            $table->decimal('refund_seller_co_funded_voucher', 15, 2)->default(0);
            $table->decimal('platform_discounts', 15, 2)->default(0);
            $table->decimal('refund_platform_discounts', 15, 2)->default(0);
            $table->decimal('platform_co_funded_vouchers', 15, 2)->default(0);
            $table->decimal('refund_platform_co_funded_vouchers', 15, 2)->default(0);
            $table->decimal('seller_shipping_cost_discount', 15, 2)->default(0);
            $table->integer('estimated_weight_g')->nullable();
            $table->integer('actual_weight_g')->nullable();
            $table->text('shopping_center_items')->nullable();
            $table->string('order_source');
            $table->timestamps();
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
