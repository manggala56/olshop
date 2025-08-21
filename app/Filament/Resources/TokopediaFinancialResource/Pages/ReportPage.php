<?php

namespace App\Filament\Resources\TokopediaFinancialResource\Pages;

use App\Filament\Resources\TokopediaFinancialResource;
use Filament\Resources\Pages\Page;

class ReportPage extends Page
{
    protected static string $resource = TokopediaFinancialResource::class;

    protected static string $view = 'filament.resources.tokopedia-financial-resource.pages.report-page';
    protected static ?string $navigationLabel = 'Laporan Keuangan';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Tiktok';
}
