<?php

namespace App\Providers;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\ServiceProvider;
use App\Models\laporan_keuangan;
use App\Observers\LaporanKeuanganObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        FilamentColor::register([
            'primary' => Color::Green,
        ]);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        laporan_keuangan::observe(LaporanKeuanganObserver::class);
    }
}
