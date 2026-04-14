<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoaResource\Pages;
use App\Models\Coa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
// INI BAGIAN YANG TADI KURANG:
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class CoaResource extends Resource
{
    protected static ?string $model = Coa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_akun')
    ->required()
    ->reactive()
    ->afterStateUpdated(function ($state, callable $set) {
        if (str_starts_with($state, '1')) {
            $set('tipe_akun', 'Harta');
        } elseif (str_starts_with($state, '2')) {
            $set('tipe_akun', 'Utang');
        } elseif (str_starts_with($state, '3')) {
            $set('tipe_akun', 'Modal');
        } elseif (str_starts_with($state, '4')) {
            $set('tipe_akun', 'Pendapatan');
        } elseif (str_starts_with($state, '5')) {
            $set('tipe_akun', 'Beban');
        }
    }),
                TextInput::make('nama_akun')
                    ->required(),
                Select::make('tipe_akun')
                    ->options([
                        'Harta' => 'Harta',
                        'Utang' => 'Utang',
                        'Modal' => 'Modal',
                        'Pendapatan' => 'Pendapatan',
                        'Beban' => 'Beban',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_akun')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nama_akun')
                    ->searchable(),
                TextColumn::make('tipe_akun'),
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
            'index' => Pages\ListCoas::route('/'),
            'create' => Pages\CreateCoa::route('/create'),
            'edit' => Pages\EditCoa::route('/{record}/edit'),
        ];
    }
}