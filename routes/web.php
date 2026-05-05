<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/force-update-db-99', function () {
    Artisan::call('migrate:install'); // Pastikan tabel migrasi ada
    Artisan::call('migrate', ['--force' => true]);
    return "Database updated: " . Artisan::output();
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/track', function () {
    return view('track');
})->name('public.track');

Route::get('/cek-tarif', function () {
    return view('tarif');
})->name('public.tarif');

Route::middleware(['auth'])->group(function () {
    Route::get('/print/invoice/{record}', [App\Http\Controllers\PrintController::class, 'invoice'])->name('print.invoice');
    Route::get('/print/manifest/{record}', [App\Http\Controllers\PrintController::class, 'manifest'])->name('print.manifest');
    Route::get('/print/resi/bulk', [App\Http\Controllers\PrintController::class, 'resiThermalBulk'])->name('print.resi.bulk');
    Route::get('/print/resi/{record}', [App\Http\Controllers\PrintController::class, 'resiThermal'])->name('print.resi');
    Route::get('/admin/pods/{filename}', [App\Http\Controllers\PodController::class, 'show'])->name('admin.pods.show');
});
