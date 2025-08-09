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

class TransactionTokopediaResource extends Resource
{
    protected static ?string $model = Transaction_tokopedia::class;
    protected static ?string $pluralModelLabel = 'Laporan Penjualan Tiktok';
    protected static ?string $navigationLabel = 'Penjualan';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Tiktok';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_id')->required()->unique(ignoreRecord: true),
                Forms\Components\Select::make('type')->options(['Order' => 'Order'])->required(),
                Forms\Components\DateTimePicker::make('order_created_at'),
                Forms\Components\DateTimePicker::make('order_settled_at'),
                Forms\Components\TextInput::make('currency'),
                Forms\Components\TextInput::make('total_settlement_amount')->numeric(),
                Forms\Components\TextInput::make('total_revenue')->numeric(),
                Forms\Components\TextInput::make('total_fees')->numeric(),
                Forms\Components\TextInput::make('shipping_cost')->numeric(),
                Forms\Components\TextInput::make('order_source'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactionTokopedias::route('/'),
            'create' => Pages\CreateTransactionTokopedia::route('/create'),
            'edit' => Pages\EditTransactionTokopedia::route('/{record}/edit'),
        ];
    }
}
