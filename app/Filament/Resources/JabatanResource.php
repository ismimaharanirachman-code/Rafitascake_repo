<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JabatanResource\Pages;
use App\Models\Jabatan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JabatanResource extends Resource
{
    protected static ?string $model = Jabatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Jabatan';
    protected static ?string $pluralModelLabel = 'Jabatan';

    public static function form(Form $form): Form
    {
        return $form->schema([
           Forms\Components\TextInput::make('id_jabatan')
                ->label('ID Jabatan')
                ->disabled()
                ->dehydrated(false)
                ->default(function () {
                    $last = \App\Models\Jabatan::orderBy('id_jabatan', 'desc')->first();

                    if (!$last) {
                        return 'JB-01';
                    }

                    $lastNumber = (int) str_replace('JB-', '', $last->id_jabatan);
                    $nextNumber = $lastNumber + 1;

                    return 'JB-' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
                })
                ->helperText('ID ini akan digunakan otomatis saat data disimpan'),

            Forms\Components\TextInput::make('nama_jabatan')
                ->label('Nama Jabatan')
                ->required()
                ->maxLength(100)
                ->unique(ignoreRecord: true),

            Forms\Components\Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->rows(3)
                ->maxLength(255)
                ->nullable(),

            Forms\Components\TextInput::make('gaji_pokok')
                ->label('Gaji Pokok')
                ->numeric()
                ->required()
                ->prefix('Rp'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_jabatan')
                    ->label('ID Jabatan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_jabatan')
                    ->label('Nama Jabatan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('gaji_pokok')
                    ->label('Gaji Pokok')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Dibuat')
                    ->sortable(),
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
            'index' => Pages\ListJabatans::route('/'),
            'create' => Pages\CreateJabatan::route('/create'),
            'edit' => Pages\EditJabatan::route('/{record}/edit'),
        ];
    }
}