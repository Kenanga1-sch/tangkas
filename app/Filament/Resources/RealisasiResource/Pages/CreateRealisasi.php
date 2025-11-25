<?php

namespace App\Filament\Resources\RealisasiResource\Pages;

use App\Filament\Resources\RealisasiResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRealisasi extends CreateRecord
{
    // PASTE BARIS INI:
    protected static string $resource = RealisasiResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}