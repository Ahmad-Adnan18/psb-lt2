<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SantriController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/santri/export', [SantriController::class, 'export'])->name('santri.export');
        Route::patch('/santri/{santri}/update-status', [SantriController::class, 'updateStatus'])->name('santri.updateStatus');
        Route::get('/dokumen/{dokumen}/download', [SantriController::class, 'downloadDokumen'])->name('dokumen.download');
        Route::post('/santri/{santri}/simpan-hasil-tes', [SantriController::class, 'simpanHasilTes'])->name('santri.simpanHasilTes');
        Route::get('/santri/{santri}/download-pdf', [SantriController::class, 'downloadPDF'])->name('santri.downloadPDF');


    Route::controller(SantriController::class)->prefix('santri')->name('santri.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{calonSantri}', 'show')->name('show'); // <-- ROUTE BARU
        Route::get('/{calonSantri}/edit', 'edit')->name('edit');
        Route::put('/{calonSantri}', 'update')->name('update');
        Route::delete('/{calonSantri}', 'destroy')->name('destroy');
    });
});

require __DIR__.'/auth.php';
