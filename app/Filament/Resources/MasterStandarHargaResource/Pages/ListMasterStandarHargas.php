<?php

namespace App\Filament\Resources\MasterStandarHargaResource\Pages;

use App\Filament\Resources\MasterStandarHargaResource;
use App\Imports\MasterStandarHargaImport;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ListMasterStandarHargas extends ListRecords
{
    protected static string $resource = MasterStandarHargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tombol Tambah Manual (Bawaan)
            Actions\CreateAction::make(),

            // Tombol Custom Import Excel
            Actions\Action::make('importExcel')
                ->label('Import SSH/SBU')
                ->color('success')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    Select::make('jenis_standar')
                        ->label('Jenis Data')
                        ->options([
                            'SSH' => 'SSH (Standar Satuan Harga)',
                            'SBU' => 'SBU (Standar Biaya Umum)',
                            'HSPK' => 'HSPK (Harga Satuan Pokok Kegiatan)',
                            'ASB' => 'ASB (Analisis Standar Belanja)',
                        ])
                        ->required(),
                    FileUpload::make('file_excel')
                        ->label('Upload File Excel/CSV')
                        ->disk('local') // Simpan sementara di storage local
                        ->directory('temp-imports')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    // Proses Import saat tombol 'Submit' ditekan
                    $filePath = Storage::disk('local')->path($data['file_excel']);
                    
                    Excel::import(new MasterStandarHargaImport($data['jenis_standar']), $filePath);

                    // Bersihkan file temp setelah selesai
                    Storage::disk('local')->delete($data['file_excel']);

                    // Notifikasi Sukses
                    \Filament\Notifications\Notification::make()
                        ->title('Data berhasil diimport!')
                        ->success()
                        ->send();
                }),
        ];
    }
}