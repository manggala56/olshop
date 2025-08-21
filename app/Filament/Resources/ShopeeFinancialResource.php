<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShopeeFinancialResource\Pages;
use App\Filament\Resources\ShopeeFinancialResource\RelationManagers;
use App\Models\financial_data_shopee;
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
use App\Services\ImportFinacialShopee;


class ShopeeFinancialResource extends Resource
{
    protected static ?string $model = financial_data_shopee::class;
    protected static ?string $pluralModelLabel = 'Laporan Pendapatan Shopee';
    protected static ?string $navigationLabel = 'Pendapatan';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Shopee';
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
                ->action(function (array $data, ImportFinacialShopee $importer): void {
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
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order_status')
                    ->label('Status Pesanan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Selesai' => 'success',
                        'Dibatalkan' => 'danger',
                        'Dikembalikan' => 'danger',
                        'Perlu Dikirim' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->limit(30),
                    Tables\Columns\TextColumn::make('product_sku')
                    ->label('SKU')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_product_price')
                    ->label('Total Harga Produk')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('admin_fee')
                    ->label('Biaya Admin')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_payment')
                    ->label('Total Pembayaran')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('net_income')
                    ->label('Penghasilan Bersih')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_discount')
                    ->label('Diskon')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('shipping_cost_paid_by_buyer')
                    ->label('Ongkos Kirim')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('order_created_at')
                    ->label('Waktu Pesanan Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('buyer_username')
                    ->label('Username Pembeli')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('shipping_province')
                    ->label('Provinsi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diimpor Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product_sku')
                ->label('Filter by SKU')
                ->options(
                    fn (): array => financial_data_shopee::query()
                        ->distinct()
                        ->pluck('product_sku', 'product_sku')
                        ->toArray()
                ),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListShopeeFinancials::route('/'),
            'create' => Pages\CreateShopeeFinancial::route('/create'),
            // 'edit' => Pages\EditShopeeFinancial::route('/{record}/edit'),
        ];
    }
}
