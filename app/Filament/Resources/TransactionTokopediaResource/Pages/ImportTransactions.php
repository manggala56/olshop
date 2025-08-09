<?php

namespace App\Filament\Resources\TransactionTokopediaResource\Pages;

use App\Filament\Resources\TransactionTokopediaResource;
use Filament\Resources\Pages\Page;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TransactionImport;

class ImportTransactions extends Page
{
    protected static string $resource = TransactionTokopediaResource::class;

    protected static string $view = 'filament.resources.transaction-tokopedia-resource.pages.import-transactions';
    use WithFileUploads;

    public $file;
    public function submit()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new TransactionImport, $this->file->getRealPath());

        session()->flash('message', 'Data berhasil diimpor!');
        $this->redirect(TransactionResource::getUrl());
    }


}
