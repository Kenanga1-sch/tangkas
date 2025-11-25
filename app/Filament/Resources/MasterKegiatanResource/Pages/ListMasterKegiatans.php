<?php

namespace App\Filament\Resources\MasterKegiatanResource\Pages;

use App\Filament\Resources\MasterKegiatanResource;
use App\Imports\MasterKegiatanImport;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ListMasterKegiatans extends ListRecords
{
    protected static string $resource = MasterKegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            
            // Tombol Import Kegiatan
            Actions\Action::make('importKegiatan')
                ->label('Import Kegiatan')
                ->color('warning') // Warna oranye biar beda
                ->icon('heroicon-o-document-arrow-up')
                ->form([
                    FileUpload::make('file_excel')
                        ->label('Upload File Kegiatan (Excel/CSV)')
                        ->disk('local')
                        ->directory('temp-imports')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $filePath = Storage::disk('local')->path($data['file_excel']);
                    
                    Excel::import(new MasterKegiatanImport, $filePath);

                    Storage::disk('local')->delete($data['file_excel']);

                    \Filament\Notifications\Notification::make()
                        ->title('Data Kegiatan berhasil diimport!')
                        ->success()
                        ->send();
                }),
        ];
    }
}