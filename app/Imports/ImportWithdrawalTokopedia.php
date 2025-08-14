<?php

namespace App\Imports;

        use App\Models\withdrawal_tokopedia;
        use Maatwebsite\Excel\Concerns\ToModel;
        use Maatwebsite\Excel\Concerns\WithStartRow;
        use Maatwebsite\Excel\Concerns\WithValidation;
        use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
        use Carbon\Carbon;

        class ImportWithdrawalTokopedia implements ToModel, WithStartRow, WithValidation,SkipsEmptyRows
        {
            /**
            * @param array $row
            *
            * @return \Illuminate\Database\Eloquent\Model|null
            */
            public function model(array $row)
            {
                if (collect($row)->filter()->isEmpty()) {
                    return null;
                }

                // Skip if empty reference ID (column B) - this check is still good for rows with some data but no reference_id
                if (empty($row[1])) {
                    return null;
                }

                return new withdrawal_tokopedia([
                    'type' => $row[0] ?? null, // A
                    'reference_id' => $row[1] ?? null, // B
                    'request_time_utc' => isset($row[2]) ? Carbon::createFromFormat('Y/m/d', $row[2]) : null, // C
                    'amount' => $row[3] ?? 0, // D
                    'status' => $row[4] ?? null, // E
                    'success_time_utc' => isset($row[5]) ? Carbon::createFromFormat('Y/m/d', $row[5]) : null, // F
                    'bank_account' => $row[6] ?? null, // G
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
                    '0' => 'required', // type
                    '1' => 'required', // reference_id
                    '2' => 'required|date_format:Y/m/d', // request_time_utc
                    '3' => 'required|numeric', // amount
                    '4' => 'required', // status
                ];
            }
        }
