<?php

namespace App\Filament\Resources\AnggaranResource\Pages;

use App\Filament\Resources\AnggaranResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAnggaran extends CreateRecord
{
    // BARIS INI WAJIB ADA
    protected static string $resource = AnggaranResource::class;
    
    // Opsional: Redirect ke tabel setelah simpan
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}