<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanPenjualanResource\Pages;
use App\Filament\Resources\LaporanPenjualanResource\RelationManagers;
use App\Models\laporan_penjualan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LaporanPenjualanResource extends Resource
{
    protected static ?string $model = laporan_penjualan::class;
    protected static ?string $navigationLabel = 'Laporan Penjualan';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 2;
    protected static ?string $pluralModelLabel = 'Laporan Penjualan';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_laporan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tanggal_laporan')
                    ->required(),
                Forms\Components\TextInput::make('jumlah_laporan')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_laporan'),
                Tables\Columns\TextColumn::make('tanggal_laporan'),
                Tables\Columns\TextColumn::make('jumlah_laporan'),
                Tables\Columns\TextColumn::make('keterangan_laporan'),
                Tables\Columns\TextColumn::make('status_laporan'),
                Tables\Columns\TextColumn::make('kategori_laporan'),
                Tables\Columns\TextColumn::make('opsional'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_laporan')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanPenjualans::route('/'),
            'create' => Pages\CreateLaporanPenjualan::route('/create'),
            'edit' => Pages\EditLaporanPenjualan::route('/{record}/edit'),
        ];
    }
}
