<?php

namespace App\Filament\Resources\TransactionTokopediaResource\Pages;

use App\Filament\Resources\TransactionTokopediaResource;
use App\Imports\TransactionImport;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Maatwebsite\Excel\Facades\Excel;

class ImportTransactions extends Page
{
    use InteractsWithForms;

    protected static string $resource = TransactionTokopediaResource::class;

    protected static string $view = 'filament.resources.transaction-tokopedia-resource.pages.import-transactions';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }
    protected function getActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali ke Daftar Penjualan')
                ->url(TransactionTokopediaResource::getUrl('index'))
                ->color('primary')
                ->icon('heroicon-o-arrow-left'),
        ];
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('attachment')
                    ->label('File Excel')
                    ->required()
                    // Menerima format file xls dan xlsx
                    ->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    // Sebaiknya jangan simpan file setelah diimpor untuk menghemat ruang
                    ->storeFiles(false),
            ])
            ->statePath('data');
    }

    public function submit()
    {

        $file = $this->form->getState()['attachment'];

        Excel::import(new TransactionImport, $file);
        Notification::make()
            ->title('Import Berhasil')
            ->body('Data transaksi dari file Excel berhasil diimpor.')
            ->success()
            ->send();

        return redirect(static::getResource()::getUrl('index'));
    }
}
