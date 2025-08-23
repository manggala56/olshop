<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanKeuanganResource\Pages;
use App\Filament\Resources\LaporanKeuanganResource\RelationManagers;
use App\Models\FinancialDataView;
use App\Models\DetailedFinancialReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class LaporanKeuanganResource extends Resource
{
    protected static ?string $model = DetailedFinancialReport::class;
    protected static ?string $navigationLabel = 'Laporan Keuangan';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 1;
    protected static ?string $pluralModelLabel = 'Laporan Keuangan';
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';


    public static function table(Table $table): Table
    {

        return $table
        ->columns([
            Tables\Columns\TextColumn::make('source')
                ->label('Platform')
                ->formatStateUsing(fn ($state) => ucfirst($state))
                ->sortable()
                ->toggleable(),

            Tables\Columns\TextColumn::make('order_id')
                ->label('ID Pesanan')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('product_name')
                ->label('Produk')
                ->searchable()
                ->sortable()
                ->toggleable(),

            Tables\Columns\TextColumn::make('quantity')
                ->label('Qty')
                ->numeric()
                ->sortable(),

            Tables\Columns\TextColumn::make('unit_price')
                ->label('Harga Unit')
                ->money('IDR')
                ->getStateUsing(fn ($record) => $record->unit_price * 1000)
                ->sortable(query: function (Builder $query, string $direction) {
                    // Sorting berdasarkan nilai asli
                    $query->orderBy('unit_price', $direction);
                })
                ->toggleable(),

            // Bagian Discounts
            Tables\Columns\TextColumn::make('subtotal_before_discount')
                ->label('Subtotal Sebelum Diskon')
                ->money('IDR')
                ->sortable()
                ->toggleable(),

            Tables\Columns\TextColumn::make('platform_discount')
                ->label('Diskon Platform')
                ->money('IDR')
                ->sortable()
                ->toggleable()
                ->color('danger'),

            Tables\Columns\TextColumn::make('seller_discount')
                ->label('Diskon Penjual')
                ->money('IDR')
                ->sortable()
                ->toggleable()
                ->color('danger'),

            Tables\Columns\TextColumn::make('subtotal_after_discount')
                ->label('Subtotal Setelah Diskon')
                ->money('IDR')
                ->sortable()
                ->toggleable()
                ->color('success'),

            // Bagian Biaya-biaya
            Tables\Columns\TextColumn::make('shipping_fee')
                ->label('Biaya Pengiriman')
                ->money('IDR')
                ->sortable()
                ->toggleable()
                ->color('warning'),

            Tables\Columns\TextColumn::make('admin_fee')
                ->label('Biaya Admin (10%)')
                ->money('IDR')
                ->sortable()
                ->color('danger'),

            Tables\Columns\TextColumn::make('affiliate_fee')
                ->label('Biaya Afiliasi')
                ->money('IDR')
                ->sortable()
                ->toggleable()
                ->color('danger')
                ->description(fn ($record) => $record->source === 'tokopedia' ? 'Dari data transaction_tokopedias' : 'Tidak ada biaya afiliasi'),

            Tables\Columns\TextColumn::make('additional_fees')
                ->label('Biaya Tambahan')
                ->money('IDR')
                ->sortable()
                ->toggleable()
                ->color('danger')
                ->description(fn ($record) => $record->source === 'tokopedia' ? 'Buyer service + Handling fee' : 'Tidak ada biaya tambahan'),

            Tables\Columns\TextColumn::make('insurance_fees')
                ->label('Biaya Asuransi')
                ->money('IDR')
                ->sortable()
                ->toggleable()
                ->color('danger')
                ->description(fn ($record) => $record->source === 'tokopedia' ? 'Shipping + Item insurance' : 'Tidak ada biaya asuransi'),

            // Bagian Total
            Tables\Columns\TextColumn::make('total_amount')
                ->label('Total Amount')
                ->money('IDR')
                ->sortable()
                ->color('info'),

            Tables\Columns\TextColumn::make('refund_amount')
                ->label('Jumlah Refund')
                ->money('IDR')
                ->sortable()
                ->toggleable()
                ->color('danger'),

            Tables\Columns\TextColumn::make('net_sales')
                ->label('Penjualan Bersih')
                ->money('IDR')
                ->sortable()
                ->color('success'),

            Tables\Columns\TextColumn::make('net_income')
                ->label('Pendapatan Bersih')
                ->money('IDR')
                ->sortable()
                ->color('success')
                ->weight('bold'),

            Tables\Columns\TextColumn::make('order_date')
                ->label('Tanggal Pesanan')
                ->dateTime()
                ->sortable(),

            Tables\Columns\TextColumn::make('order_status')
                ->label('Status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'completed', 'delivered' => 'success',
                    'processed', 'shipped' => 'info',
                    'cancelled', 'refunded' => 'danger',
                    default => 'gray',
                }),
        ])
        ->filters([
            SelectFilter::make('source')
                ->label('Platform')
                ->options([
                    'tokopedia' => 'Tokopedia',
                    'shopee' => 'Shopee',
                ]),

            SelectFilter::make('sku')
                ->label('SKU')
                ->options(function () {
                    $tokopediaSkus = DB::table('finacial_data_tokopedias')
                        ->distinct()
                        ->pluck('sku_category', 'sku_category')
                        ->filter();

                    $shopeeSkus = DB::table('finacial_data_shopees')
                        ->distinct()
                        ->pluck('product_sku', 'product_sku')
                        ->filter();

                    return $tokopediaSkus->union($shopeeSkus)->toArray();
                })
                ->searchable(),

            Filter::make('order_date')
                ->label('Tanggal Pesanan')
                ->form([
                    Forms\Components\DatePicker::make('start_date')
                        ->label('Dari Tanggal'),
                    Forms\Components\DatePicker::make('end_date')
                        ->label('Sampai Tanggal'),
                ])
                ->query(function (Builder $query, array $data) {
                    return $query
                        ->when(
                            $data['start_date'] ?? null,
                            fn (Builder $query, $date) => $query->whereDate('order_date', '>=', $date)
                        )
                        ->when(
                            $data['end_date'] ?? null,
                            fn (Builder $query, $date) => $query->whereDate('order_date', '<=', $date)
                        );
                })
                ->indicateUsing(function (array $data): ?string {
                    if (!($data['start_date'] ?? null) && !($data['end_date'] ?? null)) {
                        return null;
                    }

                    $indicators = [];
                    if ($data['start_date'] ?? null) {
                        $indicators[] = 'Dari: ' . Carbon::parse($data['start_date'])->format('d/m/Y');
                    }
                    if ($data['end_date'] ?? null) {
                        $indicators[] = 'Sampai: ' . Carbon::parse($data['end_date'])->format('d/m/Y');
                    }

                    return implode(' ', $indicators);
                })
        ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),

            ])
            ->defaultSort('order_date', 'desc')
            ->deferLoading()
            ->striped();

    }

    public static function getEloquentQuery(): Builder
    {
        $combinedQuery = DetailedFinancialReport::getDetailedReportQuery();

        return DetailedFinancialReport::query()->fromSub($combinedQuery, 'detailed_financial_reports');
    }
    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanKeuangans::route('/'),
            'create' => Pages\CreateLaporanKeuangan::route('/create'),
            'edit' => Pages\EditLaporanKeuangan::route('/{record}/edit'),
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
}
