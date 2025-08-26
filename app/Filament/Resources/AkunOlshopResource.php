<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AkunOlshopResource\Pages;
use App\Filament\Resources\AkunOlshopResource\RelationManagers;
use App\Models\akun;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class AkunOlshopResource extends Resource
{
    protected static ?string $model = akun::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $pluralModelLabel = 'List Akun Olshop';
    protected static ?string $navigationLabel = 'Data Akun Olshop';
    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('name_akun')
                ->label('Nama Akun')
                ->required()
                ->maxLength(255),
            Select::make('category')
                ->label('Kategori')
                ->options([
                    'Tokopedia' => 'Tokopedia',
                    'Shopee' => 'Shopee',
                ])
                ->required(),
            TextInput::make('optional')
                ->label('Optional')
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('name_akun')
                ->label('Nama Akun')
                ->searchable()
                ->sortable(),
            TextColumn::make('category')
                ->label('Kategori')
                ->searchable()
                ->sortable(),
            TextColumn::make('optional')
                ->label('Optional')
                ->searchable(),
            TextColumn::make('created_at')
                ->label('Dibuat Pada')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
                ->label('Diperbarui Pada')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true), //
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
            'index' => Pages\ListAkunOlshops::route('/'),
            'create' => Pages\CreateAkunOlshop::route('/create'),
            'edit' => Pages\EditAkunOlshop::route('/{record}/edit'),
        ];
    }
}
