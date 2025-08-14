<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;

class ImportMasterTokopedia implements WithMultipleSheets
{
    /**
     * @return array
     */
    use WithConditionalSheets;
    public function conditionalSheets(): array
    {
        Log::info("Success Parsing Header");
        return [
            // 0 => new ImportTransactionTokopedia(),
            // 2 => new ImportWithdrawalTokopedia(),
        ];
    }
}
