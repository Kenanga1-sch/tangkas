<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterStandarHargaResource\Pages;
use App\Filament\Resources\MasterStandarHargaResource\RelationManagers;
use App\Models\MasterStandarHarga;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterStandarHargaResource extends Resource
{
    protected static ?string $model = MasterStandarHarga::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar'; // Saya ganti ikon sedikit agar relevan dengan harga
    protected static ?string $navigationGroup = 'Master Data'; // Opsional: Mengelompokkan di sidebar

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('jenis_standar')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('kode_kelompok_barang')
                    ->maxLength(255),
                Forms\Components\TextInput::make('uraian_kelompok_barang')
                    ->maxLength(255),
                Forms\Components\TextInput::make('id_standar_harga')
                    ->maxLength(255),
                Forms\Components\TextInput::make('kode_barang')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('uraian_barang')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('spesifikasi')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('satuan')
                    ->maxLength(255),
                Forms\Components\TextInput::make('harga_satuan')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('kode_rekening')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jenis_standar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kode_kelompok_barang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('uraian_kelompok_barang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('id_standar_harga')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kode_barang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('satuan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('harga_satuan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kode_rekening')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListMasterStandarHargas::route('/'),
            'create' => Pages\CreateMasterStandarHarga::route('/create'),
            'edit' => Pages\EditMasterStandarHarga::route('/{record}/edit'),
        ];
    }

    // Hanya Admin yang boleh akses
    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }
}