<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRencana = \App\Models\Anggaran::sum('total_anggaran');
        $totalRealisasi = \App\Models\Realisasi::sum('total_realisasi');
    
        // Hitung Sisa
        $sisa = $totalRencana - $totalRealisasi;
    
        return [
            \Filament\Widgets\StatsOverviewWidget\Stat::make('Total Rencana Anggaran', 'Rp ' . number_format($totalRencana, 0, ',', '.'))
                ->description('Akumulasi semua kegiatan')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            
            \Filament\Widgets\StatsOverviewWidget\Stat::make('Total Realisasi (Serapan)', 'Rp ' . number_format($totalRealisasi, 0, ',', '.'))
                ->description('Dana yang sudah terpakai')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
            
            \Filament\Widgets\StatsOverviewWidget\Stat::make('Sisa Anggaran', 'Rp ' . number_format($sisa, 0, ',', '.'))
                ->description('Dana belum terpakai')
                ->color($sisa < 0 ? 'danger' : 'primary'), // Merah kalau minus (bahaya!)
            ];
    }
}
