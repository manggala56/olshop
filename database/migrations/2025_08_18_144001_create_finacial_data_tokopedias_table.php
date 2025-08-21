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
        Schema::create('finacial_data_tokopedias', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->string('order_status')->nullable();
            $table->string('product_name')->nullable();
            $table->integer('quantity')->nullable();
            $table->bigInteger('sku_unit_original_price')->nullable();
            $table->string('sku_category')->nullable();
            $table->bigInteger('sku_subtotal_before_discount')->nullable();
            $table->bigInteger('sku_platform_discount')->nullable();
            $table->bigInteger('sku_seller_discount')->nullable();
            $table->bigInteger('sku_subtotal_after_discount')->nullable();
            $table->bigInteger('shipping_fee_after_discount')->nullable();
            $table->bigInteger('original_shipping_fee')->nullable();
            $table->bigInteger('shipping_fee_seller_discount')->nullable();
            $table->bigInteger('shipping_fee_platform_discount')->nullable();
            $table->bigInteger('payment_platform_discount')->nullable();
            $table->bigInteger('buyer_service_fee')->nullable();
            $table->bigInteger('handling_fee')->nullable();
            $table->bigInteger('shipping_insurance')->nullable();
            $table->bigInteger('item_insurance')->nullable();
            $table->bigInteger('order_amount')->nullable();
            $table->bigInteger('order_refund_amount')->nullable();
            $table->timestamp('created_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finacial_data_tokopedias');
    }
};
