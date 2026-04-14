<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelangganResource\Pages;
use App\Models\Pelanggan;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

// Components
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Pelanggan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_pelanggan')
                    ->label('Nama Pelanggan')
                    ->required()
                    ->maxLength(100),

                Textarea::make('alamat')
                    ->label('Alamat')
                    ->required()
                    ->rows(3),

                TextInput::make('nomor_hp')
                    ->label('Nomor HP')
                    ->tel()
                    ->required()
                    ->maxLength(15),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_pelanggan')
                    ->label('Kode Pelanggan')
                    ->formatStateUsing(fn ($state) => 'P' . str_pad($state, 3, '0', STR_PAD_LEFT))
                    ->sortable(),

                TextColumn::make('nama_pelanggan')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->wrap(),

                TextColumn::make('nomor_hp')
                    ->label('Nomor HP'),
            ])
            ->filters([])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPelanggans::route('/'),
            'create' => Pages\CreatePelanggan::route('/create'),
            'edit' => Pages\EditPelanggan::route('/{record}/edit'),
        ];
    }
}