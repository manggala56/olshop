<?php

namespace App\Imports;

use App\Models\financial_data_shopee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Maatwebsite\Excel\Concerns\WithBatchInserts;
// use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Carbon\Carbon;

class financial_shopee implements ToModel,WithStartRow,SkipsEmptyRows
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (empty($row[0])) {
            return null;
        }
        $totalDiscount = $row[20] ?? 0;
        $sellerDiscount = $row[21] ?? 0;
        $shopeeDiscount = $row[22] ?? 0;
        $shippingCost = $row[34] ?? 0;
        $totalPayment = $row[37] ?? 0;
        $totalPayment = (float)str_replace('.', '', $totalPayment);
        $totalProductPrice = (float)str_replace('.', '', $row[19]);

        return new financial_data_shopee([
            'order_number'              => $row[0],
            'order_status'              => $row[1],
            'product_name'              => $row[12],
            'product_sku'               => $row[13],
            'quantity'                  => $row[17],
            'product_price'             => $row[16], // Assuming this is the 'Harga Setelah Diskon'
            'total_product_price'       => $totalProductPrice,
            'total_discount'            => $totalDiscount,
            'seller_discount'           => $sellerDiscount,
            'shopee_discount'           => $shopeeDiscount,
            'shipping_cost_paid_by_buyer' => $shippingCost,
            'total_payment'             => $totalPayment,
            'buyer_username'            => $row[41],
            'shipping_province'         => $row[46],
            'order_created_at'          => \Carbon\Carbon::createFromFormat('Y-m-d H:i', $row[8]),
            'payment_made_at'           => \Carbon\Carbon::createFromFormat('Y-m-d H:i', $row[9]),
        ]);
    }
    public function startRow(): int
    {
        return 2;
    }
}
