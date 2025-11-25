<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnggaranResource\Pages;
use App\Filament\Resources\AnggaranResource\RelationManagers;
use App\Models\Anggaran;
use App\Models\Realisasi; // <--- Import Model Realisasi
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification; // <--- Import Notifikasi

class AnggaranResource extends Resource
{
    protected static ?string $model = Anggaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Input Anggaran';
    protected static ?string $pluralModelLabel = 'Data Anggaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- BAGIAN HEADER (KEGIATAN) ---
                \Filament\Forms\Components\Section::make('Informasi Kegiatan')
                    ->schema([
                        \Filament\Forms\Components\Select::make('kode_sub_kegiatan')
                            ->label('Pilih Kegiatan')
                            ->options(\App\Models\MasterKegiatan::all()->pluck('uraian_sub_kegiatan', 'kode_sub_kegiatan'))
                            ->searchable() 
                            ->required()
                            ->columnSpanFull(),
                        
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
                        
                        // Field Tanda Tangan
                        \Filament\Forms\Components\Section::make('Data Pejabat / Tanda Tangan')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('nama_kepala_sekolah')->label('Nama Kepsek'),
                                \Filament\Forms\Components\TextInput::make('nama_bendahara')->label('Nama Bendahara'),
                            ])
                            ->columns(2)
                            ->collapsed(), 
                    ]),

                // --- BAGIAN RINCIAN (BARANG) ---
                \Filament\Forms\Components\Section::make('Rincian Belanja')
                    ->schema([
                        \Filament\Forms\Components\Repeater::make('details')
                            ->relationship()
                            ->schema([
                                \Filament\Forms\Components\Select::make('master_standar_harga_id')
                                    ->label('Cari Barang (SSH/SBU)')
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
                                    ->placeholder('Nama barang akan muncul otomatis...'),

                                \Filament\Forms\Components\Grid::make(3)->schema([
                                    \Filament\Forms\Components\TextInput::make('qty')
                                        ->numeric()
                                        ->default(1)
                                        ->live()
                                        ->afterStateUpdated(fn ($state, Get $get, Set $set) => 
                                            $set('total_harga', (int)$state * (int)$get('harga_satuan'))
                                        ),

                                    \Filament\Forms\Components\TextInput::make('harga_satuan')
                                        ->numeric()
                                        ->live()
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
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $items = $get('details');
                                $grandTotal = collect($items)->sum(fn ($item) => (int)($item['qty'] ?? 0) * (int)($item['harga_satuan'] ?? 0));
                                $set('total_anggaran', $grandTotal);
                            }),
                            
                        \Filament\Forms\Components\TextInput::make('total_anggaran')
                            ->label('Total Anggaran Kegiatan')
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
                Tables\Columns\TextColumn::make('total_anggaran')
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
                
                // TOMBOL CETAK PDF
                Tables\Actions\Action::make('cetak')
                    ->label('Cetak PDF')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn (Anggaran $record) => route('laporan.perencanaan', $record->id))
                    ->openUrlInNewTab(),

                // TOMBOL MAGIC: REALISASIKAN
                Tables\Actions\Action::make('realisasikan')
                    ->label('Buat Realisasi')
                    ->icon('heroicon-o-document-check')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Buat Dokumen Realisasi?')
                    ->modalDescription('Sistem akan menyalin data rencana ini ke menu Realisasi. Anda bisa mengedit jumlah/harganya nanti.')
                    ->action(function (Anggaran $record) {
                        // 1. Cek apakah sudah pernah direalisasikan?
                        $cek = Realisasi::where('anggaran_id', $record->id)->first();
                        if ($cek) {
                            Notification::make()->title('Gagal')->body('Realisasi untuk kegiatan ini sudah ada!')->danger()->send();
                            return;
                        }

                        // 2. Buat Header Realisasi (Copy dari Anggaran)
                        $realisasi = Realisasi::create([
                            'anggaran_id' => $record->id,
                            'kode_sub_kegiatan' => $record->kode_sub_kegiatan,
                            'tahun' => $record->tahun,
                            'bulan' => $record->bulan,
                            'nama_kepala_sekolah' => $record->nama_kepala_sekolah,
                            'nip_kepala_sekolah' => $record->nip_kepala_sekolah,
                            'nama_bendahara' => $record->nama_bendahara,
                            'nip_bendahara' => $record->nip_bendahara,
                            'total_realisasi' => $record->total_anggaran,
                        ]);

                        // 3. Copy Detail Item Satu per Satu
                        foreach ($record->details as $detail) {
                            $realisasi->details()->create([
                                'uraian' => $detail->uraian,
                                'spesifikasi' => $detail->spesifikasi,
                                'qty' => $detail->qty,
                                'satuan' => $detail->satuan,
                                'harga_satuan' => $detail->harga_satuan,
                                'total_harga' => $detail->total_harga,
                            ]);
                        }

                        Notification::make()->title('Berhasil')->body('Data disalin ke menu Realisasi')->success()->send();
                    }),
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
            'index' => Pages\ListAnggarans::route('/'),
            'create' => Pages\CreateAnggaran::route('/create'),
            'edit' => Pages\EditAnggaran::route('/{record}/edit'),
        ];
    }
}