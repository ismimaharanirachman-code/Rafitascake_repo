<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PegawaiResource\Pages;
use App\Models\Pegawai;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// Components
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

// Actions
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkActionGroup;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationLabel = 'Pegawai';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $modelLabel = 'Pegawai';
    protected static ?string $pluralModelLabel = 'Pegawai';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('nama_pegawai')
                    ->label('Nama Pegawai')
                    ->required()
                    ->maxLength(50),

                TextInput::make('jabatan')
                    ->label('Jabatan')
                    ->required()
                    ->maxLength(50),

                TextInput::make('alamat_pegawai')
                    ->label('Alamat')
                    ->required()
                    ->maxLength(100),

                TextInput::make('no_hp')
                    ->label('No HP')
                    ->tel()
                    ->required()
                    ->minLength(10)
                    ->maxLength(15),

                TextInput::make('gaji')
                    ->label('Gaji')
                    ->prefix('Rp')
                    ->required()
                    ->formatStateUsing(
                        fn ($state) =>
                        number_format((float) $state, 0, ',', '.')
                    )
                    ->dehydrateStateUsing(
                        fn ($state) =>
                        str_replace('.', '', $state)
                    )
                    ->placeholder('Contoh: 3.000.000'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('id_pegawai')
                    ->label('Kode Pegawai')
                    ->formatStateUsing(
                        fn ($state) =>
                        'PG' . str_pad($state, 3, '0', STR_PAD_LEFT)
                    )
                    ->sortable(),

                TextColumn::make('nama_pegawai')
                    ->label('Nama Pegawai')
                    ->searchable(),

                TextColumn::make('jabatan')
                    ->label('Jabatan'),

                TextColumn::make('alamat_pegawai')
                    ->label('Alamat')
                    ->wrap(),

                TextColumn::make('no_hp')
                    ->label('No HP'),

                TextColumn::make('gaji')
                    ->label('Gaji')
                    ->formatStateUsing(
                        fn ($state) =>
                        'Rp ' . number_format($state, 0, ',', '.')
                    )
                    ->sortable(),

            ])
            ->filters([])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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