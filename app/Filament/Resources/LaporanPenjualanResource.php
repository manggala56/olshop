<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanPenjualanResource\Pages;
use App\Filament\Resources\LaporanPenjualanResource\RelationManagers;
use App\Filament\Resources\LaporanPenjualanResource\Widgets;
use App\Models\finacial_data_tokopedia;
use App\Models\financial_data_shopee;
use App\Models\CombinedFinancialData;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class LaporanPenjualanResource extends Resource
{
    protected static ?string $model = null;
    protected static ?string $navigationLabel = 'Laporan Penjualan';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 2;
    protected static ?string $pluralModelLabel = 'Laporan Penjualan';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Forms\Components\TextInput::make('nama_laporan')
    //                 ->required()
    //                 ->maxLength(255),
    //             Forms\Components\DatePicker::make('tanggal_laporan')
    //                 ->required(),
    //             Forms\Components\TextInput::make('jumlah_laporan')
    //                 ->required(),
    //         ]);
    // }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('source')
                    ->label('Sumber')
                    ->formatStateUsing(fn ($state) => $state === 'tokopedia' ? 'Tokopedia' : 'Shopee')
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_id')
                    ->label('ID Pesanan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Nama Produk')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->sortable()
                    ->numeric(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Pendapatan')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('net_income')
                    ->label('Pendapatan Bersih')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_date')
                    ->label('Tanggal Pesanan')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('source')
                    ->label('Sumber')
                    ->options([
                        'tokopedia' => 'Tokopedia',
                        'shopee' => 'Shopee',
                    ]),
                SelectFilter::make('sku')
                    ->label('SKU')
                    ->options(function () {
                        $tokopediaSkus = DB::table('finacial_data_tokopedias')
                            ->distinct()
                            ->pluck('sku_category', 'sku_category');

                        $shopeeSkus = DB::table('finacial_data_shopees')
                            ->distinct()
                            ->pluck('product_sku', 'product_sku');

                        return $tokopediaSkus->union($shopeeSkus)->toArray();
                    }),
                Filter::make('order_date')
                    ->label('Tanggal Pesanan')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date) => $query->whereDate('order_date', '>=', $date)
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date) => $query->whereDate('order_date', '<=', $date)
                            );
                    })
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Nonaktifkan bulk actions karena data hanya bisa dilihat
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        // Menggunakan subquery untuk mendapatkan data gabungan
        $combinedQuery = CombinedFinancialData::getCombinedQuery();

        // Perbaikan: Gunakan model langsung, bukan self::$model
        return CombinedFinancialData::query()->fromSub($combinedQuery, 'combined_financial_data');
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
            'index' => Pages\ListLaporanPenjualans::route('/'),
            // 'create' => Pages\CreateLaporanPenjualan::route('/create'),
            // 'edit' => Pages\EditLaporanPenjualan::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
    public static function getWidgets(): array
    {
        return [
            // Widgets\FinancialOverview::class,
            Widgets\FinancialReport::class
        ];
    }
}
