<?php

namespace App\Filament\Resources\AnggaranResource\Pages;

use App\Filament\Resources\AnggaranResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;

class EditAnggaran extends EditRecord
{
    // BARIS INI WAJIB ADA
    protected static string $resource = AnggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    // Opsional: Redirect ke tabel setelah simpan
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}