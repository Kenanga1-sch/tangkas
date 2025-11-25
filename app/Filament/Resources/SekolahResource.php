<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SekolahResource\Pages;
use App\Filament\Resources\SekolahResource\RelationManagers;
use App\Models\Sekolah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SekolahResource extends Resource
{
    protected static ?string $model = Sekolah::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2'; // Ganti ikon gedung biar cocok
    protected static ?string $navigationLabel = 'Profil Sekolah';
    protected static ?string $pluralModelLabel = 'Data Sekolah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- BAGIAN 1: IDENTITAS SEKOLAH ---
                \Filament\Forms\Components\Section::make('Identitas Sekolah')
                    ->description('Informasi dasar sekolah untuk keperluan Kop Surat.')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('nama_sekolah')
                            ->label('Nama Sekolah')
                            ->required()
                            ->default('SDN CONTOH')
                            ->maxLength(255),
                            
                        \Filament\Forms\Components\TextInput::make('kabupaten')
                            ->label('Kabupaten / Kota')
                            ->required()
                            ->default('INDRAMAYU')
                            ->maxLength(255),

                        \Filament\Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        \Filament\Forms\Components\TextInput::make('telepon')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->maxLength(255),

                        \Filament\Forms\Components\FileUpload::make('logo')
                            ->label('Logo Sekolah')
                            ->image()
                            ->directory('logo-sekolah') // Folder penyimpanan di storage/app/public/logo-sekolah
                            ->disk('public')          // Wajib public agar bisa diakses browser/PDF
                            ->visibility('public')
                            ->imagePreviewHeight('100'),
                    ])
                    ->columns(2),

                // --- BAGIAN 2: PEJABAT PENANDATANGAN ---
                \Filament\Forms\Components\Section::make('Pejabat Penandatangan')
                    ->description('Nama pejabat yang akan muncul di bagian tanda tangan laporan.')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('nama_kepala_sekolah')
                            ->label('Nama Kepala Sekolah'),
                        
                        \Filament\Forms\Components\TextInput::make('nip_kepala_sekolah')
                            ->label('NIP Kepala Sekolah'),

                        \Filament\Forms\Components\TextInput::make('nama_bendahara')
                            ->label('Nama Bendahara'),

                        \Filament\Forms\Components\TextInput::make('nip_bendahara')
                            ->label('NIP Bendahara'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo')
                    ->circular(),
                    
                Tables\Columns\TextColumn::make('nama_sekolah')
                    ->label('Sekolah')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('kabupaten')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('nama_kepala_sekolah')
                    ->label('Kepala Sekolah')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('nama_bendahara')
                    ->label('Bendahara')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('telepon')
                    ->searchable()
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
            'index' => Pages\ListSekolahs::route('/'),
            'create' => Pages\CreateSekolah::route('/create'),
            'edit' => Pages\EditSekolah::route('/{record}/edit'),
        ];
    }

    // BAGIAN INI DITAMBAHKAN:
    // Hanya Admin yang boleh akses menu Sekolah
    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }
}