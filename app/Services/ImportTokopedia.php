<?php

namespace App\Services;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use App\Imports\ImportMasterTokopedia;
use Illuminate\Support\Facades\Log;
class ImportTokopedia
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function importIncomeFile(string $filePath): bool
    {
        try {
            // Dapatkan jalur absolut ke file di storage disk 'local'
            $fullPath = Storage::disk('local')->path($filePath);

            // Impor file Excel menggunakan master importer yang menangani multiple sheets
            Excel::import(new ImportMasterTokopedia(), $fullPath);
            Log::info('Import send Success');
            // Hapus file dari storage setelah impor berhasil untuk membersihkan
            Storage::disk('local')->delete($filePath);
            Log::info('Import delete success');
            Notification::make()
                ->title('Impor Berhasil')
                ->body('Data order dan catatan penarikan berhasil diimpor.')
                ->success()
                ->send();

            return true;
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Tangani kegagalan validasi dari Maatwebsite/Excel
            $failures = $e->failures();
            $errorMessage = 'Beberapa baris gagal divalidasi:';
            foreach ($failures as $failure) {
                $errorMessage .= "<br>Baris " . $failure->row() . ": " . implode(", ", $failure->errors());
            }

            Notification::make()
                ->title('Impor Gagal (Validasi)')
                ->body($errorMessage)
                ->danger()
                ->send();
            return false;
        } catch (\Exception $e) {
            // Tangani kesalahan umum lainnya
            Notification::make()
                ->title('Impor Gagal')
                ->body($e->getMessage())
                ->danger()
                ->send();
            return false;
        }
    }
}
