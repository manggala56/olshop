<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TokopediaFinancialResource\Pages;
use App\Filament\Resources\TokopediaFinancialResource\RelationManagers;
use App\Models\finacial_data_tokopedia;
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
use Filament\Notifications\Notification;
use App\Services\ImportFinacialTokopedia;

class TokopediaFinancialResource extends Resource
{
    protected static ?string $model = finacial_data_tokopedia::class;
    protected static ?string $pluralModelLabel = 'Laporan Penjualan Tiktok';
    protected static ?string $navigationLabel = 'Penjualan';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Tiktok';
    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->headerActions([
            Action::make('import')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-on-square')
                ->color('success')
                ->action(function (array $data, ImportFinacialTokopedia $importer): void {
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
                Tables\Columns\TextColumn::make('created_time')
                ->label('Tanggal')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('order_id')
                ->label('ID Pesanan')
                ->searchable(),
            Tables\Columns\TextColumn::make('order_status')
                ->label('Status Pesanan')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Dikirim' => 'warning',
                    'Dibatalkan' => 'danger',
                    'Selesai' => 'success',
                    default => 'gray',
                })
                ->searchable(),
            Tables\Columns\TextColumn::make('product_name')
                ->label('Nama Produk')
                ->searchable()
                ->limit(50),
            Tables\Columns\TextColumn::make('sku_category')
                ->label(' SKU Produk')
                ->searchable()
                ->limit(50),
            Tables\Columns\TextColumn::make('quantity')
                ->label('Jumlah')
                ->sortable(),
            Tables\Columns\TextColumn::make('order_amount')
                ->label('Jumlah Pesanan')
                ->money('IDR')
                ->sortable(),
            Tables\Columns\TextColumn::make('order_refund_amount')
                ->label('Jumlah Refund')
                ->money('IDR')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sku_category')
                ->label('Filter by SKU')
                ->options(
                    fn (): array => finacial_data_tokopedia::query()
                        ->distinct()
                        ->pluck('sku_category', 'sku_category')
                        ->toArray()
                ),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTokopediaFinancials::route('/'),
            'create' => Pages\CreateTokopediaFinancial::route('/create'),
            'report' => Pages\ReportPage::route('/report'),
            // 'edit' => Pages\EditTokopediaFinancial::route('/{record}/edit'),
        ];
    }
}
