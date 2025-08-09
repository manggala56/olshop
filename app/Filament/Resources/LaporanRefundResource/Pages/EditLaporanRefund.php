<?php

namespace App\Filament\Resources\LaporanRefundResource\Pages;

use App\Filament\Resources\LaporanRefundResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanRefund extends EditRecord
{
    protected static string $resource = LaporanRefundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
