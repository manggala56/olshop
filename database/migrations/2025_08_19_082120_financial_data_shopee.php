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
        Schema::create('finacial_data_shopees', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->string('order_status');
            $table->string('product_name');
            $table->string('product_sku');
            $table->integer('quantity');
            $table->decimal('product_price', 12, 2)->nullable();
            $table->decimal('total_product_price', 12, 2)->nullable();
            $table->decimal('total_discount', 12, 2)->nullable();
            $table->decimal('seller_discount', 12, 2)->nullable();
            $table->decimal('shopee_discount', 12, 2)->nullable();
            $table->decimal('shipping_cost_paid_by_buyer', 12, 2)->nullable();
            $table->decimal('total_payment', 12, 2)->nullable();
            $table->string('buyer_username')->nullable();
            $table->string('shipping_province')->nullable();
            $table->dateTime('order_created_at')->nullable();
            $table->dateTime('payment_made_at')->nullable();
            $table->string('store_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finacial_data_shopees');

    }
};
