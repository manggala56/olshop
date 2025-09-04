<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\Log;
// use Illuminate\Contracts\Queue\ShouldQueue;


class master_shopee implements WithMultipleSheets
{
    protected string $storeName;

    /**
    * @param Collection $collection
    */
    public function __construct(string $storeName)
    {
        $this->storeName = $storeName;
    }

    public function sheets(): array
    {
        Log::info("Success Parsing Header");
        return [
            0 => new financial_shopee($this->storeName),
        ];
    }
}
