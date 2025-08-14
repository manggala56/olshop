<?php

namespace App\Imports;

use App\Models\withdrawal_tokopedia;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ImportWithdrawalTokopedia implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    WithChunkReading,
    WithBatchInserts
{
    use SkipsFailures;

    /**
     * @var array
     */
    protected $columnMap = [
        'Type'                 => 'type',
        'Reference ID'         => 'reference_id',
        'Request time (UTC)'   => 'request_time_utc',
        'Amount'               => 'amount',
        'Status'               => 'status',
        'Success time (UTC)'   => 'success_time_utc',
        'Bank account'         => 'bank_account',
    ];

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        Log::info('Importing WithDrawal Details row:', $row);
        $data = [];
        foreach ($row as $csvHeader => $value) {
            $trimmedCsvHeader = trim($csvHeader);
            if (isset($this->columnMap[$trimmedCsvHeader])) {
                $dbColumn = $this->columnMap[$trimmedCsvHeader];
                $data[$dbColumn] = $value;
            }
        }

        // Konversi string tanggal/waktu ke objek Carbon
        $data['request_time_utc'] = isset($data['request_time_utc']) && !empty($data['request_time_utc'])
                                    ? Carbon::parse($data['request_time_utc'])->setTimezone('UTC')
                                    : null;

        $data['success_time_utc'] = isset($data['success_time_utc']) && !empty($data['success_time_utc'])
                                    ? Carbon::parse($data['success_time_utc'])->setTimezone('UTC')
                                    : null;

        // Konversi jumlah ke float
        $data['amount'] = (float) ($data['amount'] ?? 0);
        return new withdrawal_tokopedia($data);
    }

    /**
     * Tentukan aturan validasi untuk setiap baris.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'Reference ID' => [
                'required',
                'string',
                Rule::unique('withdrawal_records', 'reference_id') // Pastikan unik di tabel withdrawal_records
            ],
            'Type'               => 'required|string|max:50',
            'Request time (UTC)' => 'required|date',
            'Amount'             => 'required|numeric',
            'Status'             => 'required|string|max:50',
            'Success time (UTC)' => 'nullable|date', // Dapat null jika belum berhasil
            'Bank account'       => 'nullable|string|max:255',
        ];
    }

    /**
     * Sesuaikan pesan validasi.
     *
     * @return array
     */
    public function customValidationMessages(): array
    {
        return [
            'Reference ID.required'      => 'Kolom "Reference ID" wajib diisi.',
            'Reference ID.unique'        => 'ID Referensi ":input" sudah ada dalam database.',
            'Type.required'              => 'Kolom "Type" wajib diisi.',
            'Request time (UTC).required'=> 'Kolom "Request time (UTC)" wajib diisi.',
            'Request time (UTC).date'    => 'Format tanggal "Request time (UTC)" tidak valid.',
            'Amount.required'            => 'Kolom "Amount" wajib diisi.',
            'Amount.numeric'             => 'Kolom "Amount" harus berupa angka.',
            'Status.required'            => 'Kolom "Status" wajib diisi.',
            'Success time (UTC).date'    => 'Format tanggal "Success time (UTC)" tidak valid.',
        ];
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2; // Lewati baris header
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 200;
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 200;
    }
}
