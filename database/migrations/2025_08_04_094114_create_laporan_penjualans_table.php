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
        Schema::create('laporan_penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_laporan');
            $table->string('tanggal_laporan')->nullable();
            $table->string('jumlah_laporan')->nullable();
            $table->string('keterangan_laporan')->nullable();
            $table->string('status_laporan')->nullable();
            $table->string('kategori_laporan')->nullable();
            $table->string('opsional')->nullable();
            $table->string('addon')->nullable();
            $table->string('platform')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_penjualans');
    }
};
