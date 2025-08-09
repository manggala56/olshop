<?php

namespace App\Imports;

use App\Models\Transaction_tokopedia;
use Maatwebsite\Excel\Concerns\ToModel;

class TransactionImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $parseDate = function ($value) {
            if (is_numeric($value)) {
                return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
            }
            return \Carbon\Carbon::parse($value);
        };
        return new Transaction_tokopedia([
            'order_id' => $row[0],
            'type' => $row[1],
            'order_created_at' => $parseDate($row[2]),
            'order_settled_at' => $parseDate($row[3]),
            'currency' => $row[4],
            'total_settlement_amount' => (float) $row[5],
            'total_revenue' => (float) $row[6],
            'subtotal_after_discounts' => (float) $row[7],
            'subtotal_before_discounts' => (float) $row[8],
            'seller_discounts' => (float) $row[9],
            'refund_subtotal_after_discounts' => (float) $row[10],
            'refund_subtotal_before_discounts' => (float) $row[11],
            'refund_of_seller_discounts' => (float) $row[12],
            'total_fees' => (float) $row[13],
            'tiktok_commission_fee' => (float) $row[14],
            'flat_fee' => (float) $row[15],
            'sales_fee' => (float) $row[16],
            'pre_order_service_fee' => (float) $row[17],
            'mall_service_fee' => (float) $row[18],
            'payment_fee' => (float) $row[19],
            'shipping_cost' => (float) $row[20],
            'shipping_costs_passed_to_logistic' => (float) $row[21],
            'replacement_shipping_fee' => (float) $row[22],
            'exchange_shipping_fee' => (float) $row[23],
            'shipping_cost_borne_by_platform' => (float) $row[24],
            'shipping_cost_paid_by_customer' => (float) $row[25],
            'refunded_shipping_paid_by_customer' => (float) $row[26],
            'return_shipping_passed_to_customer' => (float) $row[27],
            'shipping_cost_subsidy' => (float) $row[28],
            'affiliate_commission' => (float) $row[29],
            // Lanjutkan sesuai kebutuhan...
            'dynamic_commission' => (float) $row[30],
            'live_specials_fee' => (float) $row[31],
            'voucher_xtra_fee' => (float) $row[32],
            'eams_fee' => (float) $row[33],
            'brand_flash_sale_fee' => (float) $row[34],
            'bonus_cashback_fee' => (float) $row[35],
            'dt_handling_fee' => (float) $row[36],
            'paylater_handling_fee' => (float) $row[37],
            'adjustment_amount' => (float) $row[38],
            'related_order_id' => $row[39] ?? null,
            'customer_payment' => (float) $row[40],
            'customer_refund' => (float) $row[41],
            'seller_co_funded_voucher' => (float) $row[42],
            'refund_seller_co_funded_voucher' => (float) $row[43],
            'platform_discounts' => (float) $row[44],
            'refund_platform_discounts' => (float) $row[45],
            'platform_co_funded_vouchers' => (float) $row[46],
            'refund_platform_co_funded_vouchers' => (float) $row[47],
            'seller_shipping_cost_discount' => (float) $row[48],
            'estimated_weight_g' => (int) ($row[49] ?? null),
            'actual_weight_g' => (int) ($row[50] ?? null),
            'shopping_center_items' => $row[51],
            'order_source' => $row[52],
        ]);
    }
        public function startRow(): int
        {
            return 2;
        }

        public function rules(): array
        {
            return [
                '0' => 'required|string|unique:transactions,order_id',
                '1' => 'required|string',
                '2' => 'required|date',
                '3' => 'required|date',
                '4' => 'required|string',
                '5' => 'required|numeric',
                '6' => 'required|numeric',
            ];
        }
}
