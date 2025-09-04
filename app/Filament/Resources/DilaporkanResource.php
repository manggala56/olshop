<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DilaporkanResource\Pages;
use App\Models\laporan_keuangan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DilaporkanResource extends Resource
{
    protected static ?string $model = laporan_keuangan::class;

    protected static ?string $navigationLabel = 'Dilaporkan ';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 3;
    protected static ?string $pluralModelLabel = 'Dilaporkan';
    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';

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
                    Tables\Columns\TextColumn::make('nama_laporan')
                    ->label('Nama Laporan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_laporan')
                    ->label('Tanggal Laporan')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_laporan')
                    ->label('Jumlah Laporan')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan_laporan')
                    ->label('Keterangan')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status_laporan')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'selesai' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('kategori_laporan')
                    ->label('Kategori')
                    ->searchable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('tanggal_laporan')
                ->form([
                    Forms\Components\DatePicker::make('dari_tanggal')
                        ->label('Dari Tanggal'),
                    Forms\Components\DatePicker::make('sampai_tanggal')
                        ->label('Sampai Tanggal'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['dari_tanggal'],
                            fn (Builder $query, $date): Builder => $query->whereDate('tanggal_laporan', '>=', $date),
                        )
                        ->when(
                            $data['sampai_tanggal'],
                            fn (Builder $query, $date): Builder => $query->whereDate('tanggal_laporan', '<=', $date),
                        );
                })
                ->indicateUsing(function (array $data): array {
                    $indicators = [];
                    if ($data['dari_tanggal'] ?? null) {
                        $indicators['dari_tanggal'] = 'Dari Tanggal: ' . Carbon::parse($data['dari_tanggal'])->toFormattedDateString();
                    }
                    if ($data['sampai_tanggal'] ?? null) {
                        $indicators['sampai_tanggal'] = 'Sampai Tanggal: ' . Carbon::parse($data['sampai_tanggal'])->toFormattedDateString();
                    }

                    return $indicators;
                })
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
            'index' => Pages\ListDilaporkans::route('/'),

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
}
