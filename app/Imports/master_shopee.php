<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\Log;

class master_shopee implements WithMultipleSheets
{
    /**
    * @param Collection $collection
    */
    public function sheets():array
    {
        Log::info("Success Parsing Header");
        return[
            0 => new financial_shopee(),
        ];
    }
}
