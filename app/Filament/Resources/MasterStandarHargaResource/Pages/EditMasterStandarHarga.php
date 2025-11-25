<?php

namespace App\Filament\Resources\MasterStandarHargaResource\Pages;

use App\Filament\Resources\MasterStandarHargaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterStandarHarga extends EditRecord
{
    protected static string $resource = MasterStandarHargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
