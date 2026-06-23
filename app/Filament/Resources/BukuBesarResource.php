<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BukuBesarResource\Pages;
use App\Models\BukuBesar;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;

use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

class BukuBesarResource extends Resource
{
    protected static ?string $model = BukuBesar::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Buku Besar';

    protected static ?string $navigationGroup = 'Laporan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('tanggal')
                    ->required(),

                TextInput::make('keterangan')
                    ->required(),

                TextInput::make('akun')
                    ->required(),

                TextInput::make('debit')
                    ->numeric()
                    ->default(0),

                TextInput::make('kredit')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d/m/Y'),

                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan'),

                Tables\Columns\TextColumn::make('akun')
                    ->label('Akun'),

                Tables\Columns\TextColumn::make('debit')
                    ->label('Debit')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),

                Tables\Columns\TextColumn::make('kredit')
                    ->label('Kredit')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            ])
            ->filters([])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBukuBesars::route('/'),
        ];
    }
}