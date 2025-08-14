<?php

namespace App\Imports;

use App\Models\Transaction_tokopedia;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ImportTransactionTokopedia implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    WithChunkReading,
    WithBatchInserts
{
    use SkipsFailures; // Memungkinkan penanganan kegagalan validasi

    /**
     * @var array Peta dari nama kolom CSV ke nama kolom database.
     * Ini membantu menangani spasi tambahan atau perbedaan penamaan di CSV.
     */
    protected $columnMap = [
        'Order/adjustment ID'                           => 'order_id',
        'Type'                                          => 'type',
        'Order created time(UTC)'                       => 'order_created_at',
        'Order settled time(UTC)'                       => 'order_settled_at',
        'Currency'                                      => 'currency',
        'Total settlement amount'                       => 'total_settlement_amount',
        'Total revenue'                                 => 'total_revenue',
        'Subtotal after seller discounts'               => 'subtotal_after_seller_discounts',
        'Subtotal before discounts'                     => 'subtotal_before_discounts',
        'Seller discounts'                              => 'seller_discounts',
        'Refund subtotal after seller discounts'        => 'refund_subtotal_after_seller_discounts',
        'Refund subtotal before seller discounts'       => 'refund_subtotal_before_seller_discounts',
        'Refund of seller discounts'                    => 'refund_of_seller_discounts',
        'Total fees'                                    => 'total_fees',
        'TikTok Shop commission fee'                    => 'tiktok_shop_commission_fee',
        'Flat fee'                                      => 'flat_fee',
        'Sales fee'                                     => 'sales_fee',
        'Pre-Order Service Fee'                         => 'pre_order_service_fee',
        'Mall service fee'                              => 'mall_service_fee',
        'Payment fee'                                   => 'payment_fee',
        'Shipping cost'                                 => 'shipping_cost',
        'Shipping costs passed on to the logistics provider' => 'shipping_costs_passed_on_to_logistics_provider',
        'Replacement shipping fee (passed on to the customer)' => 'replacement_shipping_fee_passed_on_to_customer',
        'Exchange shipping fee (passed on to the customer)' => 'exchange_shipping_fee_passed_on_to_customer',
        'Shipping cost borne by the platform'           => 'shipping_cost_borne_by_platform',
        'Shipping cost paid by the customer'            => 'shipping_cost_paid_by_customer',
        'Refunded shipping cost paid by the customer'   => 'refunded_shipping_cost_paid_by_customer',
        'Return shipping costs (passed on to the customer)' => 'return_shipping_costs_passed_on_to_customer',
        'Shipping cost subsidy'                         => 'shipping_cost_subsidy',
        'Affiliate commission'                          => 'affiliate_commission',
        'Affiliate partner commission'                  => 'affiliate_partner_commission',
        'Affiliate Shop Ads commission'                 => 'affiliate_shop_ads_commission',
        'SFP service fee'                               => 'sfp_service_fee',
        'Dynamic Commission'                            => 'dynamic_commission',
        'LIVE Specials Service Fee'                     => 'live_specials_service_fee',
        'Voucher Xtra Service Fee'                      => 'voucher_xtra_service_fee',
        'EAMS Program service fee'                      => 'eams_program_service_fee',
        'Brand Crazy Deals/Flash Sale service fee'      => 'brand_crazy_deals_flash_sale_service_fee',
        'Bonus cashback service fee'                    => 'bonus_cashback_service_fee',
        'DT Handling Fee'                               => 'dt_handling_fee',
        'PayLater Handling Fee'                         => 'paylater_handling_fee',
        'Ajustment amount'                              => 'adjustment_amount',
        'Related order ID'                              => 'related_order_id',
        'Customer payment'                              => 'customer_payment',
        'Customer refund'                               => 'customer_refund',
        'Seller co-funded voucher discount'             => 'seller_co_funded_voucher_discount',
        'Refund of seller co-funded voucher discount'   => 'refund_of_seller_co_funded_voucher_discount',
        'Platform discounts'                            => 'platform_discounts',
        'Refund of platform discounts'                  => 'refund_of_platform_discounts',
        'Platform co-funded voucher discounts'          => 'platform_co_funded_voucher_discounts',
        'Refund of platform co-funded voucher discounts' => 'refund_of_platform_co_funded_voucher_discounts',
        'Seller shipping cost discount'                 => 'seller_shipping_cost_discount',
        'Estimated package weight (g)'                  => 'estimated_package_weight_g',
        'Actual package weight (g)'                     => 'actual_package_weight_g',
        'Shopping center items'                         => 'shopping_center_items',
        'Order Source'                                  => 'order_source',
    ];


    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Peta untuk menyimpan data yang sudah diolah dan siap dimasukkan ke model
        $data = [];

        // Iterasi melalui setiap kolom di baris CSV
        foreach ($row as $csvHeader => $value) {
            // Trim spasi dari header CSV untuk memastikan kecocokan dengan $columnMap
            $trimmedCsvHeader = trim($csvHeader);

            // Jika header CSV ada di peta kita, petakan ke nama kolom database
            if (isset($this->columnMap[$trimmedCsvHeader])) {
                $dbColumn = $this->columnMap[$trimmedCsvHeader];
                $data[$dbColumn] = $value;
            }
        }

        // Lakukan pengolahan dan konversi tipe data untuk kolom-kolom tertentu
        $data['order_created_at'] = isset($data['order_created_at']) && !empty($data['order_created_at'])
                                    ? Carbon::parse($data['order_created_at'])->setTimezone('UTC')
                                    : null;

        $data['order_settled_at'] = isset($data['order_settled_at']) && !empty($data['order_settled_at'])
                                    ? Carbon::parse($data['order_settled_at'])->setTimezone('UTC')
                                    : null;

        // Konversi nilai numerik ke float, default 0 jika kosong
        $numericFields = [
            'total_settlement_amount', 'total_revenue', 'subtotal_after_seller_discounts',
            'subtotal_before_discounts', 'seller_discounts', 'refund_subtotal_after_seller_discounts',
            'refund_subtotal_before_discounts', 'refund_of_seller_discounts', 'total_fees',
            'tiktok_shop_commission_fee', 'flat_fee', 'sales_fee', 'pre_order_service_fee',
            'mall_service_fee', 'payment_fee', 'shipping_cost', 'shipping_costs_passed_on_to_logistics_provider',
            'replacement_shipping_fee_passed_on_to_customer', 'exchange_shipping_fee_passed_on_to_customer',
            'shipping_cost_borne_by_platform', 'shipping_cost_paid_by_customer',
            'refunded_shipping_cost_paid_by_customer', 'return_shipping_costs_passed_on_to_customer',
            'shipping_cost_subsidy', 'affiliate_commission', 'affiliate_partner_commission',
            'affiliate_shop_ads_commission', 'sfp_service_fee', 'dynamic_commission',
            'live_specials_service_fee', 'voucher_xtra_service_fee', 'eams_program_service_fee',
            'brand_crazy_deals_flash_sale_service_fee', 'bonus_cashback_service_fee',
            'dt_handling_fee', 'paylater_handling_fee', 'adjustment_amount', 'customer_payment',
            'customer_refund', 'seller_co_funded_voucher_discount', 'refund_of_seller_co_funded_voucher_discount',
            'platform_discounts', 'refund_of_platform_discounts', 'platform_co_funded_voucher_discounts',
            'refund_of_platform_co_funded_voucher_discounts', 'seller_shipping_cost_discount',
        ];

        foreach ($numericFields as $field) {
            $data[$field] = (float) ($data[$field] ?? 0);
        }

        // Konversi nilai integer, default 0 jika kosong
        $intFields = [
            'estimated_package_weight_g', 'actual_package_weight_g'
        ];
        foreach ($intFields as $field) {
            $data[$field] = (int) ($data[$field] ?? 0);
        }

        return new Transaction_tokopedia($data);
    }

    /**
     * Tentukan aturan validasi untuk setiap baris.
     * Nama kunci aturan validasi harus sesuai dengan nama header CSV yang sebenarnya.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'Order/adjustment ID' => [
                'required',
                'string',
                // Pastikan order_id unik di tabel transaction_tokopedias
                Rule::unique('transaction_tokopedias', 'order_id')
            ],
            'Type'                  => 'required|string|max:50',
            'Order created time(UTC)' => 'required|date', // 'date' cukup fleksibel untuk berbagai format tanggal
            'Order settled time(UTC)' => 'required|date',
            'Currency'              => 'required|string|max:10',
            'Total settlement amount' => 'required|numeric',
            'Total revenue'         => 'required|numeric',
            'Total fees'            => 'required|numeric',
            'Shipping cost'         => 'required|numeric',
            'Order Source'          => 'required|string|max:255',
            // Tambahkan aturan validasi untuk kolom lain jika diperlukan
            // 'Subtotal after seller discounts' => 'nullable|numeric',
            // 'Estimated package weight (g)' => 'nullable|integer',
        ];
    }

    /**
     * Sesuaikan pesan validasi.
     *
     * @return array
     */
    public function customValidationMessages(): array
    {
        return [
            'Order/adjustment ID.required'      => 'Kolom "Order/adjustment ID" wajib diisi.',
            'Order/adjustment ID.unique'        => 'ID Order ":input" sudah ada dalam database.',
            'Type.required'                     => 'Kolom "Type" wajib diisi.',
            'Order created time(UTC).required'  => 'Kolom "Order created time(UTC)" wajib diisi.',
            'Order created time(UTC).date'      => 'Format tanggal "Order created time(UTC)" tidak valid.',
            'Order settled time(UTC).required'  => 'Kolom "Order settled time(UTC)" wajib diisi.',
            'Order settled time(UTC).date'      => 'Format tanggal "Order settled time(UTC)" tidak valid.',
            'Currency.required'                 => 'Kolom "Currency" wajib diisi.',
            'Total settlement amount.required'  => 'Kolom "Total settlement amount" wajib diisi.',
            'Total settlement amount.numeric'   => 'Kolom "Total settlement amount" harus berupa angka.',
            'Total revenue.required'            => 'Kolom "Total revenue" wajib diisi.',
            'Total revenue.numeric'             => 'Kolom "Total revenue" harus berupa angka.',
            'Total fees.required'               => 'Kolom "Total fees" wajib diisi.',
            'Total fees.numeric'                => 'Kolom "Total fees" harus berupa angka.',
            'Shipping cost.required'            => 'Kolom "Shipping cost" wajib diisi.',
            'Shipping cost.numeric'             => 'Kolom "Shipping cost" harus berupa angka.',
            'Order Source.required'             => 'Kolom "Order Source" wajib diisi.',
        ];
    }

    /**
     * Tentukan baris awal untuk impor data (lewati header).
     *
     * @return int
     */
    public function startRow(): int
    {
        return 2; // Lewati baris pertama (header)
    }

    /**
     * Tentukan ukuran chunk untuk membaca file.
     * Berguna untuk file besar agar tidak memakan banyak memori.
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000; // Proses 1000 baris sekaligus
    }

    /**
     * Tentukan ukuran batch untuk memasukkan data.
     *
     * @return int
     */
    public function batchSize(): int
    {
        return 1000; // Masukkan 1000 data sekaligus ke database
    }
}
