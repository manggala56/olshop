<?php

namespace App\Observers;

use App\Models\laporan_keuangan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LaporanKeuanganObserver
{
    private function syncToNustra(laporan_keuangan $laporan_keuangan, $method)
    {
        try {
            $url = env('OLSHOP_URL') . '/api/sync-laporan';
            $headers = ['X-OLSHOP-KEY' => env('OLSHOP_KEY')];
            $data = $laporan_keuangan->toArray();
            $data['tanggal_laporan'] = Carbon::parse($data['tanggal_laporan'])->toDateString();
            $data['laporan_keuangan_id'] = $laporan_keuangan->id;

            if ($method === 'DELETE') {
                $response = Http::withHeaders($headers)->delete($url . '/' . $laporan_keuangan->id);
            } else {
                $response = Http::withHeaders($headers)->post($url, ['data' => [$data]]);
            }

            if ($response->failed()) {
                Log::error("Failed to sync to Nustra ({$method}): " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Exception while syncing to Nustra: " . $e->getMessage());
        }
    }
    /**
     * Handle the laporan_keuangan "created" event.
     */
    public function created(laporan_keuangan $laporan_keuangan): void
    {
        $this->syncToNustra($laporan_keuangan, 'POST');
    }

    /**
     * Handle the laporan_keuangan "updated" event.
     */
    public function updated(laporan_keuangan $laporan_keuangan): void
    {
        $this->syncToNustra($laporan_keuangan, 'POST');

    }

    /**
     * Handle the laporan_keuangan "deleted" event.
     */
    public function deleted(laporan_keuangan $laporan_keuangan): void
    {
        $this->syncToNustra($laporan_keuangan, 'DELETE');

    }

    /**
     * Handle the laporan_keuangan "restored" event.
     */
    public function restored(laporan_keuangan $laporan_keuangan): void
    {
        //
    }

    /**
     * Handle the laporan_keuangan "force deleted" event.
     */
    public function forceDeleted(laporan_keuangan $laporan_keuangan): void
    {
        //
    }
}
