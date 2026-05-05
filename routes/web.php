<?php

use Illuminate\Support\Facades\Route;

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
    Route::get('/admin/pods/{filename}', [App\Http\Controllers\PodController::class, 'show'])->name('admin.pods.show');
});
