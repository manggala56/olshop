<?php

namespace App\Imports;

use App\Models\finacial_data_tokopedia;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Carbon\Carbon;

class financial_tokopedia implements ToModel, WithStartRow,SkipsEmptyRows,WithUpserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $createdTime = null;
        if (!empty($row[27])) {
            $createdTime = Carbon::createFromFormat('d/m/Y H:i:s', trim($row[27]));
        }
        return new finacial_data_tokopedia([
            'order_id' => $row[0],
            'order_status' => $row[1],
            'product_name' => $row[7],
            'quantity' => (int) $row[9],
            'sku_category'=>$row[6],
            'sku_unit_original_price' => (int) $row[11],
            'sku_subtotal_before_discount' => (int) $row[12],
            'sku_platform_discount' => (int) $row[13],
            'sku_seller_discount' => (int) $row[14],
            'sku_subtotal_after_discount' => (int) $row[15],
            'shipping_fee_after_discount' => (int) $row[16],
            'original_shipping_fee' => (int) $row[17],
            'shipping_fee_seller_discount' => (int) $row[18],
            'shipping_fee_platform_discount' => (int) $row[19],
            'payment_platform_discount' => (int) $row[20],
            'buyer_service_fee' => (int) $row[21],
            'handling_fee' => (int) $row[22],
            'shipping_insurance' => (int) $row[23],
            'item_insurance' => (int) $row[24],
            'order_amount' => (int) $row[25],
            'order_refund_amount' => (int) $row[26],
            'created_time' => $createdTime,
        ]);
    }
    public function startRow(): int
    {
        return 3;
    }
    public function uniqueBy()
    {
        return 'order_id';
    }
}
