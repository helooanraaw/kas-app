<?php

use Illuminate\Support\Facades\Route;

// Import semua Controller kita
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicReportController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| 1. RUTE PUBLIK (Bisa diakses siapa saja, TANPA LOGIN)
|--------------------------------------------------------------------------
|
| Ini adalah halaman laporan untuk Siswa.
|
*/
Route::get('/', [PublicReportController::class, 'index'])->name('laporan.public');


/*
|--------------------------------------------------------------------------
| 2. RUTE PRIVATE / BENDAHARA (WAJIB LOGIN)
|--------------------------------------------------------------------------
|
| Semua rute di dalam grup ini dilindungi oleh middleware 'auth'.
|
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Bendahara
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Siswa
    Route::resource('siswa', SiswaController::class);

    // CRUD PEMBAYARAN (PEMASUKAN) - Menggantikan Route lama
    // Mencakup: index, create, store, edit, update, destroy
    Route::resource('pembayaran', PembayaranController::class)->except(['show']);

    // CRUD PENGELUARAN (PENGELUARAN)
    // Mencakup: index, create, store, edit, update, destroy
    Route::resource('pengeluaran', PengeluaranController::class)->except(['show']);

    // LAPORAN KEGIATAN KAS
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // BARU: Route untuk Ekspor
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

    // Rute Profile (Bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| 3. RUTE AUTENTIKASI (Biarkan di paling bawah)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';