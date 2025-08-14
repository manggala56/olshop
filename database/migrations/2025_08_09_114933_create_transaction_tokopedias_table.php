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
            $table->string('order_id')->unique()->nullable(); // Made nullable
            $table->string('type')->nullable(); // Made nullable
            $table->timestamp('order_created_at')->nullable();
            $table->timestamp('order_settled_at')->nullable();
            $table->string('currency')->nullable(); // Made nullable
            $table->decimal('total_settlement_amount', 15, 2)->nullable(); // Made nullable
            $table->decimal('total_revenue', 15, 2)->nullable(); // Made nullable
            $table->decimal('subtotal_after_discounts', 15, 2)->nullable(); // Made nullable
            $table->decimal('subtotal_before_discounts', 15, 2)->nullable(); // Made nullable
            $table->decimal('seller_discounts', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('refund_subtotal_after_discounts', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('refund_subtotal_before_discounts', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('refund_of_seller_discounts', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('total_fees', 15, 2)->nullable(); // Made nullable
            $table->decimal('tiktok_commission_fee', 15, 2)->nullable(); // Made nullable
            $table->decimal('flat_fee', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('sales_fee', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('pre_order_service_fee', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('mall_service_fee', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('payment_fee', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('shipping_cost', 15, 2)->nullable(); // Made nullable
            $table->decimal('shipping_costs_passed_to_logistic', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('replacement_shipping_fee', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('exchange_shipping_fee', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('shipping_cost_borne_by_platform', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('shipping_cost_paid_by_customer', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('refunded_shipping_paid_by_customer', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('return_shipping_passed_to_customer', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('shipping_cost_subsidy', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('affiliate_commission', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('dynamic_commission', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('live_specials_fee', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('voucher_xtra_fee', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('eams_fee', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('brand_flash_sale_fee', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('bonus_cashback_fee', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('dt_handling_fee', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('paylater_handling_fee', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('adjustment_amount', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->string('related_order_id')->nullable();
            $table->decimal('customer_payment', 15, 2)->nullable(); // Made nullable
            $table->decimal('customer_refund', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('seller_co_funded_voucher', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('refund_seller_co_funded_voucher', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('platform_discounts', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('refund_platform_discounts', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('platform_co_funded_vouchers', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('refund_platform_co_funded_vouchers', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->decimal('seller_shipping_cost_discount', 15, 2)->default(0)->nullable(); // Made nullable, kept default
            $table->integer('estimated_weight_g')->nullable();
            $table->integer('actual_weight_g')->nullable();
            $table->text('shopping_center_items')->nullable();
            $table->string('order_source')->nullable(); // Made nullable
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
