<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukResource\Pages;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
// Import komponen agar tidak error
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationIcon = 'heroicon-o-cake'; // Icon kue

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                TextInput::make('nama_kue')
                    ->label('nama kue')
                    ->required(),
                Select::make('jenis_kue')
                    ->label('jenis kue')
                    ->options([
                        'Kue Basah' => 'Kue Basah',
                        'Kue Kering' => 'Kue Kering',
                        'Roti' => 'Roti',
                        'Cake' => 'Cake',
                    ])
                    ->required(),
                TextInput::make('harga_jual')
                    ->label('harga jual')
                    ->numeric() // Supaya hanya bisa input angka
                    ->prefix('Rp')
                    ->required(),
                TextInput::make('stok')
                    ->label('stok')
                    ->numeric()
                    ->required(),
                DatePicker::make('tanggal_produksi')
                    ->label('tanggal produksi')
                    ->required(),
                FileUpload::make('gambar')
                    ->image()
                    ->directory('produk')
                    ->disk('public'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kue')->label('nama kue')->searchable(),
                TextColumn::make('jenis_kue')->label('jenis kue'),
                TextColumn::make('harga_jual')->label('harga')->money('IDR')->sortable(),
                TextColumn::make('stok')->label('stok')->sortable(),
                TextColumn::make('tanggal_produksi')->label('tanggal produksi')->date(),
                ImageColumn::make('gambar')->disk('public')
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
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}