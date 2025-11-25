<?php

namespace App\Filament\Resources\SekolahResource\Pages;

use App\Filament\Resources\SekolahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Sekolah; // Import Model Sekolah

class ListSekolahs extends ListRecords
{
    protected static string $resource = SekolahResource::class;

    protected function getHeaderActions(): array
    {
        // Logika: Hitung jumlah data di tabel sekolahs
        $count = Sekolah::count();

        // Jika sudah ada data (lebih dari 0), tombol Create disembunyikan (return array kosong)
        if ($count > 0) {
            return [];
        }

        // Jika belum ada data, tampilkan tombol Create
        return [
            Actions\CreateAction::make(),
        ];
    }
}