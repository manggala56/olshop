<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanRefundResource\Pages;
use App\Filament\Resources\LaporanRefundResource\RelationManagers;
use App\Models\LaporanRefund;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LaporanRefundResource extends Resource
{
    protected static ?string $model = LaporanRefund::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            ->columns([
                //
            ])
            ->filters([
                //
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
            'index' => Pages\ListLaporanRefunds::route('/'),
            'create' => Pages\CreateLaporanRefund::route('/create'),
            'edit' => Pages\EditLaporanRefund::route('/{record}/edit'),
        ];
    }
}
