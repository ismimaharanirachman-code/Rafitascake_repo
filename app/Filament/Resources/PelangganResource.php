<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelangganResource\Pages;
use App\Models\Pelanggan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
// Import komponen biar nggak error merah
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;

    protected static ?string $navigationIcon = 'heroicon-o-users'; // Icon pelanggan

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id_pelanggan')
                    ->label('id Pelanggan')
                    ->required(),
                TextInput::make('nama_pelanggan')
                    ->label('nama Pelanggan')
                    ->required(),
                Textarea::make('alamat')
                    ->label('alamat')
                    ->required(),
                TextInput::make('nomor_hp')
                    ->label('nomor hp')
                    ->tel()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_pelanggan')->label('Id')->sortable(),
                TextColumn::make('nama_pelanggan')->label('Nama Pelanggann')->searchable(),
                TextColumn::make('alamat')->label('Alamat')->limit(30),
                TextColumn::make('nomor_hp')->label('Nomor HP'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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