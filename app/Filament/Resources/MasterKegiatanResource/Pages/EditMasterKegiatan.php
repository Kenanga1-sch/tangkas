<?php

namespace App\Filament\Resources\MasterKegiatanResource\Pages;

use App\Filament\Resources\MasterKegiatanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasterKegiatan extends EditRecord
{
    protected static string $resource = MasterKegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
