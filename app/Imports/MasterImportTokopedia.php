<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\Log;

class MasterImportTokopedia implements WithMultipleSheets
{
    /**
    * @param Collection $collection
    */
    public function sheets():array
    {
        Log::info("Success Parsing Header");
        return[
            0 => new ImportTransactionTokopedia(),
            2 => new ImportWithdrawalTokopedia(),
        ];
    }
}
