<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE VIEW financial_data_view AS
            SELECT
                order_number,
                order_status,
                product_name,
                total_payment,
                order_created_at,
                product_sku,
                'Shopee' AS marketplace
            FROM finacial_data_shopees
            UNION ALL
            SELECT
                order_id,
                order_status,
                product_name,
                order_amount,
                created_time,
                sku_category,
                'Tokopedia' AS marketplace
            FROM finacial_data_tokopedias
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW financial_data_view");
    }
};
