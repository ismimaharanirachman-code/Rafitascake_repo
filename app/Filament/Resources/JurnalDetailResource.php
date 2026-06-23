<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurnalDetailResource\Pages;
use App\Filament\Resources\JurnalDetailResource\RelationManagers;
use App\Models\JurnalDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JurnalDetailResource extends Resource
{
    protected static ?string $model = JurnalDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Jurnal Umum';

    protected static ?string $modelLabel = 'Jurnal Umum';

    protected static ?string $pluralModelLabel = 'Jurnal Umum';

    protected static ?string $navigationGroup = 'Laporan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
           ->columns([
    Tables\Columns\TextColumn::make('jurnal.tanggal')
        ->label('Tanggal')
        ->date('d-m-Y')
        ->sortable(),

    Tables\Columns\TextColumn::make('jurnal.keterangan')
        ->label('Keterangan')
        ->searchable(),

    Tables\Columns\TextColumn::make('akun')
        ->label('Akun')
        ->searchable(),

    Tables\Columns\TextColumn::make('debit')
        ->label('Debit')
        ->money('IDR', divideBy: 1),

    Tables\Columns\TextColumn::make('kredit')
        ->label('Kredit')
        ->money('IDR', divideBy: 1),
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
            'index' => Pages\ListJurnalDetails::route('/'),
            'create' => Pages\CreateJurnalDetail::route('/create'),
            'edit' => Pages\EditJurnalDetail::route('/{record}/edit'),
        ];
    }
}
