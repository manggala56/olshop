<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanKeuanganResource\Pages;
use App\Filament\Resources\LaporanKeuanganResource\RelationManagers;
use App\Models\laporan_keuangan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LaporanKeuanganResource extends Resource
{
    protected static ?string $model = laporan_keuangan::class;
    protected static ?string $navigationLabel = 'Laporan Keuangan';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 1;
    protected static ?string $pluralModelLabel = 'Laporan Keuangan';
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

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
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\SelectFilter::make('kategori_laporan')
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
