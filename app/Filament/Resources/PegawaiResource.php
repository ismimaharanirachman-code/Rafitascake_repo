<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PegawaiResource\Pages;
use App\Filament\Resources\PegawaiResource\RelationManagers;
use App\Models\Pegawai;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

   public static function form(Form $form): Form
{
    return $form
        ->schema([
            // Tambahkan inputan sesuai kolom di database kamu
            Forms\Components\TextInput::make('id_pegawai')
                ->required()
                ->maxLength(10),
            Forms\Components\TextInput::make('nama_pegawai')
                ->required()
                ->maxLength(50),
            Forms\Components\TextInput::make('jabatan')
                ->required()
                ->maxLength(50),
            Forms\Components\TextInput::make('alamat_pegawai')
                ->required()
                ->maxLength(100),
            Forms\Components\TextInput::make('no_hp')
                ->required()
                ->minLength(12)
                ->maxLength(12),
        ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            // Tambahkan kolom-kolom yang ingin kamu tampilkan di tabel
            Tables\Columns\TextColumn::make('id_pegawai')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('nama_pegawai')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('jabatan')
                ->sortable(),
            Tables\Columns\TextColumn::make('alamat_pegawai'),
            Tables\Columns\TextColumn::make('no_hp'),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }
}
