<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BahanBakuResource\Pages;
use App\Models\BahanBaku;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BahanBakuResource extends Resource
{
    protected static ?string $model = BahanBaku::class;

    protected static ?string $navigationIcon = 'heroicon-s-cube';
<<<<<<< Updated upstream
    
    protected static ?string $navigationLabel = 'Bahan Baku';
    
    protected static ?string $navigationGroup = 'Master Data';

     //Tambahkan ini untuk menghilangkan s
=======

    protected static ?string $navigationLabel = 'Bahan Baku';

    protected static ?string $navigationGroup = 'Master Data';

>>>>>>> Stashed changes
    protected static ?string $modelLabel = 'Bahan Baku';
    protected static ?string $pluralModelLabel = 'Bahan Baku';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([

                        Forms\Components\TextInput::make('harga')
                            ->label('Harga Per Satuan')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->default(0),

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

                        Forms\Components\DatePicker::make('expired_date')
                            ->label('Tanggal Expired')
                            ->native(false),

                        Forms\Components\Select::make('storage_location')
                            ->label('Lokasi Penyimpanan')
                            ->options([
                                'Freezer' => 'Freezer',
                                'Chiller' => 'Chiller',
                                'Rak A' => 'Rak A',
                                'Rak B' => 'Rak B',
                                'Gudang' => 'Gudang',
                            ])
                            ->searchable(),
                    ])
                    ->columns(2),
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
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state < 10 ? 'danger' : 'success'),

                Tables\Columns\TextColumn::make('satuan')
                    ->label('Satuan')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_nilai')
                    ->label('Total Nilai')
                    ->getStateUsing(fn ($record) => $record->stok * $record->harga)
                    ->money('IDR', locale: 'id'),

                Tables\Columns\TextColumn::make('expired_date')
                    ->label('Expired')
                    ->date('d M Y')
                    ->sortable()
                    ->badge()
                    ->color(function ($record) {
                        if (!$record->expired_date) {
                            return 'gray';
                        }

                        return now()->diffInDays($record->expired_date, false) <= 7
                            ? 'danger'
                            : 'success';
                    }),

                Tables\Columns\TextColumn::make('storage_location')
                    ->label('Lokasi')
                    ->badge()
                    ->color('success'),

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
                Tables\Actions\DeleteAction::make(),
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