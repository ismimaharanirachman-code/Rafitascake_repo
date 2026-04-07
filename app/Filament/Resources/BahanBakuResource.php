<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BahanBakuResource\Pages;
use App\Filament\Resources\BahanBakuResource\RelationManagers;
use App\Models\BahanBaku;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BahanBakuResource extends Resource
{
    protected static ?string $model = BahanBaku::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\TextInput::make('nama_bahan')
                        ->label('Nama Bahan Baku')
                        ->required()
                        ->placeholder('Contoh: Tepung Terigu'),

                    Forms\Components\TextInput::make('stok')
                        ->label('Jumlah Stok')
                        ->numeric()
                        ->required()
                        ->default(0),

                    Forms\Components\Select::make('satuan')
                        ->label('Satuan')
                        ->options([
                            'kg' => 'Kilogram (kg)',
                            'gram' => 'Gram (g)',
                            'pcs' => 'Pieces (pcs)',
                            'liter' => 'Liter (L)',
                        ])
                        ->required(),

                    Forms\Components\TextInput::make('harga')
                        ->label('Harga Per Satuan')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),
                ])
                ->columns(2), // Membuat inputan jadi 2 kolom agar rapi
        ]);
}

   public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('nama_bahan')
                ->label('Nama Bahan')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('stok')
                ->label('Stok')
                ->sortable(),

            Tables\Columns\TextColumn::make('satuan')
                ->label('Satuan')
                ->badge() // Membuat tampilan satuan jadi seperti label/badge
                ->color('info'),

            Tables\Columns\TextColumn::make('harga')
                ->label('Harga')
                ->money('IDR') // Otomatis format mata uang Rupiah
                ->sortable(),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Terakhir Update')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(), // Tambah tombol hapus satuan
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
            'index' => Pages\ListBahanBakus::route('/'),
            'create' => Pages\CreateBahanBaku::route('/create'),
            'edit' => Pages\EditBahanBaku::route('/{record}/edit'),
        ];
    }
}
