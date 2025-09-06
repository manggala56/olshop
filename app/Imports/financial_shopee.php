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
    public function __construct(string $storeName)
    {
        $this->storeName = $storeName;
    }
    public function model(array $row)
    {
        if (empty($row[0])) {
            return null;
        }
        return new financial_data_shopee([
            'store_name'                    => $this->storeName,
            'order_number'                  => $row[0], // Kolom A: No. Pesanan
            'order_status'                  => $row[1] ?? null, // Kolom B: Status Pesanan
            'product_name'                  => $row[13] ?? null, // Kolom N: Nama Produk
            'product_sku'                   => $row[12] ?? null, // Kolom M: SKU Induk
            'quantity'                      => $row[18] ?? 0, // Kolom S: Jumlah
            'product_price'                 => $this->parseInteger($row[16] ?? 0), // Kolom Q: Harga Awal
            'total_product_price'           => $this->parseInteger($row[20] ?? 0), // Kolom U: Total Harga Produk
            'total_discount'                => $this->parseInteger($row[21] ?? 0), // Kolom V: Total Diskon
            'seller_discount'               => $this->parseInteger($row[22] ?? 0), // Kolom W: Diskon Dari Penjual
            'shopee_discount'               => $this->parseInteger($row[23] ?? 0), // Kolom X: Diskon Dari Shopee
            'shipping_cost_paid_by_buyer'   => $this->parseInteger($row[35] ?? 0), // Kolom AJ: Ongkos Kirim Dibayar oleh Pembeli
            'total_payment'                 => $this->parseInteger($row[38] ?? 0), // Kolom AM: Total Pembayaran
            'buyer_username'                => $row[42] ?? null, // Kolom AQ: Username (Pembeli)
            'shipping_province'             => $row[47] ?? null, // Kolom AV: Provinsi
            'order_created_at'              => $this->parseDate($row[9] ?? null), // Kolom J: Waktu Pesanan Dibuat
            'payment_made_at'               => $this->parseDate($row[10] ?? null), // Kolom K: Waktu Pembayaran Dilakukan
        ]);
    }
    private function parseCurrency(string|float $value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }
        $value = str_replace(['Rp', '.', ','], ['', '', '.'], $value);
        return (float) $value;
    }

    /**
     * Helper untuk mengonversi string ke integer.
     */
    private function parseInteger(string|int $value): int
    {
        if (is_numeric($value)) {
            return (int) $value * 1000;
        }
        return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Helper untuk mengonversi string tanggal ke Carbon instance.
     */
    private function parseDate(?string $value): ?Carbon
    {
        if (empty($value)) {
            return null;
        }
        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }
    public function startRow(): int
    {
        return 2;
    }
}
