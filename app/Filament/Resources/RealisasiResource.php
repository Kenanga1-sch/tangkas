<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RealisasiResource\Pages;
use App\Filament\Resources\RealisasiResource\RelationManagers;
use App\Models\Realisasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;

class RealisasiResource extends Resource
{
    protected static ?string $model = Realisasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Input Realisasi';
    protected static ?string $pluralModelLabel = 'Data Realisasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- BAGIAN HEADER (KEGIATAN) ---
                \Filament\Forms\Components\Section::make('Informasi Kegiatan')
                    ->schema([
                        // Field 1: STANDAR PENDIDIKAN
                        \Filament\Forms\Components\Select::make('standar_pendidikan')
                            ->label('Standar Pendidikan')
                            ->options(\App\Models\MasterKegiatan::query()->distinct('standar_pendidikan')->pluck('standar_pendidikan', 'standar_pendidikan'))
                            ->searchable()
                            ->live()
                            ->dehydrated(false) // <--- PERBAIKAN 1
                            ->required(),

                        // Field 2: URAIAN KEGIATAN
                        \Filament\Forms\Components\Select::make('uraian_kegiatan')
                            ->label('Uraian Kegiatan')
                            ->options(function (\Filament\Forms\Get $get) {
                                $standar = $get('standar_pendidikan');
                                if (!$standar) { return []; }
                                return \App\Models\MasterKegiatan::query()
                                    ->where('standar_pendidikan', $standar)
                                    ->distinct('uraian_kegiatan')
                                    ->pluck('uraian_kegiatan', 'uraian_kegiatan');
                            })
                            ->searchable()
                            ->live()
                            ->dehydrated(false) // <--- PERBAIKAN 1
                            ->required(),

                        // Field 3: SUB KEGIATAN
                        \Filament\Forms\Components\Select::make('kode_sub_kegiatan')
                            ->label('Sub Kegiatan')
                            ->options(function (\Filament\Forms\Get $get) {
                                $standar = $get('standar_pendidikan');
                                $uraian = $get('uraian_kegiatan');
                                if (!$standar || !$uraian) { return []; }
                                return \App\Models\MasterKegiatan::query()
                                    ->where('standar_pendidikan', $standar)
                                    ->where('uraian_kegiatan', $uraian)
                                    ->pluck('uraian_sub_kegiatan', 'kode_sub_kegiatan');
                            })
                            ->searchable()
                            ->required()
                            ->columnSpanFull(),
                        
                        // Tahun dan Bulan
                        \Filament\Forms\Components\Grid::make(2)->schema([
                            \Filament\Forms\Components\Select::make('bulan')
                                ->options([
                                    'Januari' => 'Januari', 'Februari' => 'Februari', 'Maret' => 'Maret',
                                    'April' => 'April', 'Mei' => 'Mei', 'Juni' => 'Juni',
                                    'Juli' => 'Juli', 'Agustus' => 'Agustus', 'September' => 'September',
                                    'Oktober' => 'Oktober', 'November' => 'November', 'Desember' => 'Desember'
                                ])->required(),
                            \Filament\Forms\Components\TextInput::make('tahun')
                                ->numeric()->default(date('Y'))->required(),
                        ]),
                        
                        // Tanda Tangan
                        \Filament\Forms\Components\Section::make('Data Pejabat / Tanda Tangan')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('nama_kepala_sekolah')->label('Nama Kepsek'),
                                \Filament\Forms\Components\TextInput::make('nama_bendahara')->label('Nama Bendahara'),
                            ])
                            ->columns(2)
                            ->collapsed(), 
                    ]),

                // --- BAGIAN RINCIAN (BARANG) ---
                \Filament\Forms\Components\Section::make('Rincian Realisasi')
                    ->schema([
                        \Filament\Forms\Components\Repeater::make('details')
                            ->relationship()
                            ->schema([
                                \Filament\Forms\Components\Select::make('master_standar_harga_id')
                                    ->label('Cari Barang (SSH/SBU)')
                                    ->helperText('Kosongkan jika ingin input manual sepenuhnya')
                                    ->options(function ($state) { return []; })
                                    ->getSearchResultsUsing(function (string $search) {
                                        return \App\Models\MasterStandarHarga::query()
                                            ->where('uraian_barang', 'like', "%{$search}%")
                                            ->limit(50)
                                            ->get()
                                            ->mapWithKeys(fn ($item) => [$item->id => "{$item->uraian_barang} - Rp " . number_format($item->harga_satuan, 0, ',', '.')]);
                                    })
                                    ->searchable()
                                    ->live()
                                    ->dehydrated(false)
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        if ($state) {
                                            $barang = \App\Models\MasterStandarHarga::find($state);
                                            if ($barang) {
                                                $set('uraian', $barang->uraian_barang);
                                                $set('satuan', $barang->satuan);
                                                $set('harga_satuan', $barang->harga_satuan);
                                            }
                                        }
                                    }),

                                \Filament\Forms\Components\TextInput::make('uraian')
                                    ->required()
                                    ->placeholder('Nama barang...'),

                                \Filament\Forms\Components\Grid::make(3)->schema([
                                    \Filament\Forms\Components\TextInput::make('qty')
                                        ->numeric()
                                        ->default(1)
                                        ->live(onBlur: true) // <--- PERBAIKAN 2
                                        ->afterStateUpdated(fn ($state, Get $get, Set $set) => 
                                            $set('total_harga', (int)$state * (int)$get('harga_satuan'))
                                        ),

                                    \Filament\Forms\Components\TextInput::make('harga_satuan')
                                        ->numeric()
                                        ->live(onBlur: true) // <--- PERBAIKAN 2
                                        ->afterStateUpdated(fn ($state, Get $get, Set $set) => 
                                            $set('total_harga', (int)$get('qty') * (int)$state)
                                        ),

                                    \Filament\Forms\Components\TextInput::make('satuan'),
                                ]),

                                \Filament\Forms\Components\TextInput::make('total_harga')
                                    ->readOnly()
                                    ->numeric()
                                    ->prefix('Rp'),
                            ])
                            ->columns(1)
                            ->defaultItems(1)
                            ->live(onBlur: true) // <--- PERBAIKAN 2
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $items = $get('details');
                                $grandTotal = collect($items)->sum(fn ($item) => (int)($item['qty'] ?? 0) * (int)($item['harga_satuan'] ?? 0));
                                $set('total_realisasi', $grandTotal);
                            }),
                            
                        \Filament\Forms\Components\TextInput::make('total_realisasi')
                            ->label('Total Realisasi Kegiatan')
                            ->readOnly()
                            ->numeric()
                            ->prefix('Rp')
                            ->extraInputAttributes(['style' => 'font-size: 1.5rem; font-weight: bold; text-align: right;']),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_sub_kegiatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bulan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_kepala_sekolah')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_realisasi')
                    ->label('Total Realisasi')
                    ->numeric()
                    ->money('IDR')
                    ->sortable(),
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
                
                Tables\Actions\Action::make('cetak')
                    ->label('Cetak Realisasi')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn (Realisasi $record) => route('laporan.realisasi', $record->id))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListRealisasis::route('/'),
            'create' => Pages\CreateRealisasi::route('/create'),
            'edit' => Pages\EditRealisasi::route('/{record}/edit'),
        ];
    }
}