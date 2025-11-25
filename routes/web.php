<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return view('welcome');
});

// --- GROUP ROUTE LAPORAN ---

// 1. Cetak Rencana (Anggaran)
Route::get('/laporan/perencanaan/{id}', [LaporanController::class, 'cetakPerencanaan'])
    ->name('laporan.perencanaan');

// 2. Cetak Realisasi (BARU)
Route::get('/laporan/realisasi/{id}', [LaporanController::class, 'cetakRealisasi'])
    ->name('laporan.realisasi');