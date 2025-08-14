<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionTokopediaResource\Pages;
use App\Filament\Resources\TransactionTokopediaResource\RelationManagers;
use App\Models\Transaction_tokopedia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Imports\TransactionImport;
use Filament\Notifications\Notification;
use App\Services\ImportTokopedia;

class TransactionTokopediaResource extends Resource
{
    protected static ?string $model = Transaction_tokopedia::class;
    protected static ?string $pluralModelLabel = 'Laporan Penjualan Tiktok';
    protected static ?string $navigationLabel = 'Penjualan';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Tiktok';
    protected static ?int $navigationSort = 1;

    // The form method is commented out, so it will not be active.
    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             // Forms\Components\TextInput::make('order_id')->required()->unique(ignoreRecord: true),
    //             // Forms\Components\Select::make('type')->options(['Order' => 'Order'])->required(),
    //             // Forms\Components\DateTimePicker::make('order_created_at'),
    //             // Forms\Components\DateTimePicker::make('order_settled_at'),
    //             // Forms\Components\TextInput::make('currency'),
    //             // Forms\Components\TextInput::make('total_settlement_amount')->numeric(),
    //             // Forms\Components\TextInput::make('total_revenue')->numeric(),
    //             // Forms\Components\TextInput::make('total_fees')->numeric(),
    //             // Forms\Components\TextInput::make('shipping_cost')->numeric(),
    //             // Forms\Components\TextInput::make('order_source'),
    //         ]);
    // }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Action::make('import')
                    ->label('Import Excel')
                    ->icon('heroicon-o-arrow-up-on-square')
                    ->color('success')
                    ->action(function (array $data, ImportTokopedia $importer): void {
                        $filePath = $data['file'];
                        $importer->importIncomeFile($filePath);
                    })
                    ->form([
                        FileUpload::make('file')
                            ->label('File Excel')
                            ->required()
                            ->acceptedFileTypes([
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'text/csv',
                            ])
                            ->disk('local')
                            ->directory('imports')
                            ->preserveFilenames(),
                    ])
                    ->modalWidth('md')
                    ->modalHeading('Import Data Produk'),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('order_id')->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('order_created_at')->dateTime(),
                Tables\Columns\TextColumn::make('order_settled_at')->dateTime(),
                Tables\Columns\TextColumn::make('currency'),
                Tables\Columns\TextColumn::make('total_revenue')->money('IDR'),
                Tables\Columns\TextColumn::make('total_settlement_amount')->money('IDR'),
                Tables\Columns\TextColumn::make('total_fees')->money('IDR'),
                Tables\Columns\TextColumn::make('shipping_cost')->money('IDR'),
                Tables\Columns\TextColumn::make('order_source'),
            ])
            ->filters([
                // Add any filters here if needed
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Add any relation managers here if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactionTokopedias::route('/'),
            'edit' => Pages\EditTransactionTokopedia::route('/{record}/edit'),
            // 'import' => Pages\ImportTransactions::route('/import'), // This line is commented out, so it won't be active.
        ];
    }
}
