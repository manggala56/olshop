<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ToCollection; // Bisa pakai ToCollection atau ToModel, yang penting implementasinya
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ImportMasterTokopedia implements WithMultipleSheets
{
    /**
     * @return array
     */
    public function sheets(): array
    {
        Log::info("Success Parsing Header");
        return [
            0 => new ImportTransactionTokopedia(),
            2 => new ImportWithdrawalTokopedia(),
        ];
    }
    public function onUnknownSheet($sheetName)
    {
        // Callback ini akan dipanggil untuk setiap sheet yang ditemukan dalam file
        Log::info("Found Excel Sheet: " . $sheetName);
    }
}
