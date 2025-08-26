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
        Schema::create('withdrawal_tokopedias', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('reference_id')->unique();
            $table->dateTime('request_time_utc')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('status')->nullable();
            $table->dateTime('success_time_utc')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('store_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawal_tokopedias');
    }
};
