<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanKeuanganResource\Pages;
use App\Filament\Resources\LaporanKeuanganResource\RelationManagers;
use App\Models\FinancialDataView;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class LaporanKeuanganResource extends Resource
{
    protected static ?string $model = FinancialDataView::class;
    protected static ?string $navigationLabel = 'Laporan Keuangan';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 1;
    protected static ?string $pluralModelLabel = 'Laporan Keuangan';
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

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
            Tables\Columns\TextColumn::make('order_number')
                ->label('Nomor Pesanan')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('order_status')
                ->label('Status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'completed', 'delivered' => 'success',
                    'processing', 'shipped' => 'warning',
                    'cancelled', 'refunded' => 'danger',
                    default => 'gray',
                })
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('product_name')
                ->label('Produk')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('total_payment')
                ->label('Total')
                ->money('IDR')
                ->sortable(),
            Tables\Columns\TextColumn::make('order_created_at')
                ->label('Tanggal Pesanan')
                ->dateTime()
                ->sortable(),
            Tables\Columns\TextColumn::make('product_sku')
                ->label('SKU')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('marketplace')
                ->label('Marketplace')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Shopee' => 'blue',
                    'Tokopedia' => 'green',
                    default => 'gray',
                })
                ->searchable()
                ->sortable(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('product_sku')
                ->label('Filter by SKU')
                ->options(
                    fn (): array => FinancialDataView::query()
                        ->distinct()
                        ->pluck('product_sku', 'product_sku')
                        ->toArray()
                ),
            Tables\Filters\Filter::make('order_created_at')
                ->form([
                    Forms\Components\DatePicker::make('from')
                        ->label('Dari Tanggal'),
                    Forms\Components\DatePicker::make('until')
                        ->label('Sampai Tanggal'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('order_created_at', '>=', $date),
                        )
                        ->when(
                            $data['until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('order_created_at', '<=', $date),
                        );
                }),
        ])
            ->actions([
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
}
