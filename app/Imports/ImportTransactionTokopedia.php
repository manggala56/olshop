<?php

namespace App\Imports;

        use App\Models\Transaction_tokopedia;
        use Maatwebsite\Excel\Concerns\ToModel;
        use Maatwebsite\Excel\Concerns\WithStartRow;
        use Maatwebsite\Excel\Concerns\WithValidation;
        use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
        use Carbon\Carbon;

        class ImportTransactionTokopedia implements ToModel, WithStartRow, WithValidation,SkipsEmptyRows
        {
            /**
            * @param array $row
            *
            * @return \Illuminate\Database\Eloquent\Model|null
            */
            public function model(array $row)
            {
                // Skip if empty order ID (column A)
                if (empty($row[0])) {
                    return null;
                }

                return new Transaction_tokopedia([
                    'order_id' => $row[0] ?? null, // A
                    'type' => $row[1] ?? null, // B
                    'order_created_at' => isset($row[2]) ? Carbon::createFromFormat('Y/m/d', $row[2]) : null, // C
                    'order_settled_at' => isset($row[3]) ? Carbon::createFromFormat('Y/m/d', $row[3]) : null, // D
                    'currency' => $row[4] ?? null, // E
                    'total_settlement_amount' => $row[5] ?? 0, // F
                    'total_revenue' => $row[6] ?? 0, // G
                    'subtotal_after_discounts' => $row[7] ?? 0, // H
                    'subtotal_before_discounts' => $row[8] ?? 0, // I
                    'seller_discounts' => $row[9] ?? 0, // J
                    'refund_subtotal_after_discounts' => $row[10] ?? 0, // K
                    'refund_subtotal_before_discounts' => $row[11] ?? 0, // L
                    'refund_of_seller_discounts' => $row[12] ?? 0, // M
                    'total_fees' => $row[13] ?? 0, // N
                    'tiktok_commission_fee' => $row[14] ?? 0, // O
                    'flat_fee' => $row[15] ?? 0, // P
                    'sales_fee' => $row[16] ?? 0, // Q
                    'pre_order_service_fee' => $row[17] ?? 0, // R
                    'mall_service_fee' => $row[18] ?? 0, // S
                    'payment_fee' => $row[19] ?? 0, // T
                    'shipping_cost' => $row[20] ?? 0, // U
                    'shipping_costs_passed_to_logistic' => $row[21] ?? 0, // V
                    'replacement_shipping_fee' => $row[22] ?? 0, // W
                    'exchange_shipping_fee' => $row[23] ?? 0, // X
                    'shipping_cost_borne_by_platform' => $row[24] ?? 0, // Y
                    'shipping_cost_paid_by_customer' => $row[25] ?? 0, // Z
                    'refunded_shipping_paid_by_customer' => $row[26] ?? 0, // AA
                    'return_shipping_passed_to_customer' => $row[27] ?? 0, // AB
                    'shipping_cost_subsidy' => $row[28] ?? 0, // AC
                    'affiliate_commission' => $row[29] ?? 0, // AD
                    'dynamic_commission' => $row[32] ?? 0, // AG (skipping AE, AF)
                    'live_specials_fee' => $row[33] ?? 0, // AH
                    'voucher_xtra_fee' => $row[34] ?? 0, // AI
                    'eams_fee' => $row[35] ?? 0, // AJ
                    'brand_flash_sale_fee' => $row[36] ?? 0, // AK
                    'bonus_cashback_fee' => $row[37] ?? 0, // AL
                    'dt_handling_fee' => $row[38] ?? 0, // AM
                    'paylater_handling_fee' => $row[39] ?? 0, // AN
                    'adjustment_amount' => $row[40] ?? 0, // AO
                    'related_order_id' => $row[41] ?? null, // AP
                    'customer_payment' => $row[43] ?? 0, // AR (skipping AQ)
                    'customer_refund' => $row[44] ?? 0, // AS
                    'seller_co_funded_voucher' => $row[45] ?? 0, // AT
                    'refund_seller_co_funded_voucher' => $row[46] ?? 0, // AU
                    'platform_discounts' => $row[47] ?? 0, // AV
                    'refund_platform_discounts' => $row[48] ?? 0, // AW
                    'platform_co_funded_vouchers' => $row[49] ?? 0, // AX
                    'refund_platform_co_funded_vouchers' => $row[50] ?? 0, // AY
                    'seller_shipping_cost_discount' => $row[51] ?? 0, // AZ
                    'estimated_weight_g' => $row[52] ?? 0, // BA
                    'actual_weight_g' => $row[53] ?? 0, // BB
                    'shopping_center_items' => $row[54] ?? null, // BC
                    'order_source' => $row[55] ?? null, // BD
                ]);
            }

            /**
             * @return int
             */
            public function startRow(): int
            {
                return 2; // Skip header row
            }

            /**
             * @return array
             */
            public function rules(): array
            {
                return [
                    '0' => 'required', // order_adjustment_id
                    '1' => 'required', // type
                    '2' => 'required|date_format:Y/m/d', // order_created_time_utc
                    '3' => 'required|date_format:Y/m/d', // order_settled_time_utc
                    '4' => 'required', // currency
                ];
            }
        }
