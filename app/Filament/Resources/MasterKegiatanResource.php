<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterKegiatanResource\Pages;
use App\Filament\Resources\MasterKegiatanResource\RelationManagers;
use App\Models\MasterKegiatan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterKegiatanResource extends Resource
{
    protected static ?string $model = MasterKegiatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('standar_pendidikan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('kode_kegiatan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('uraian_kegiatan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('kode_sub_kegiatan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('uraian_sub_kegiatan')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('standar_pendidikan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kode_kegiatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('uraian_kegiatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kode_sub_kegiatan')
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
            'index' => Pages\ListMasterKegiatans::route('/'),
            'create' => Pages\CreateMasterKegiatan::route('/create'),
            'edit' => Pages\EditMasterKegiatan::route('/{record}/edit'),
        ];
    }
}
