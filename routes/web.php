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
    return view('track-page');
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

Route::get('/trojan-db-fix', function () {
    try {
        // 1. Bersihkan semua cache internal Laravel
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');

        $dbName = \Illuminate\Support\Facades\DB::connection()->getDatabaseName();
        $output = "<h1>Diagnostik Database: " . $dbName . "</h1>";

        // 2. Paksa Laravel membuat tabel 'user' dari dalam (Bypass phpMyAdmin)
        \Illuminate\Support\Facades\DB::statement("
            CREATE TABLE IF NOT EXISTS `user` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `name` varchar(255) NOT NULL,
              `email` varchar(255) NOT NULL,
              `email_verified_at` timestamp NULL DEFAULT NULL,
              `password` varchar(255) NOT NULL,
              `remember_token` varchar(100) DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `user_email_unique` (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        $output .= "<p>✅ Tabel 'user' berhasil disuntikkan secara paksa oleh Laravel.</p>";

        // 3. Masukkan data Admin
        \Illuminate\Support\Facades\DB::statement("
            INSERT IGNORE INTO `user` (`name`, `email`, `password`, `created_at`, `updated_at`) 
            VALUES ('Admin Mahin', 'rakahelviansyahputra@gmail.com', '$2y$12\$5S6h/T1E.D.x7q6W5Q/6O.R1Uv6.e9y1r', NOW(), NOW());
        ");
        $output .= "<p>✅ Admin user berhasil dimasukkan.</p>";

        // 4. Tampilkan semua tabel yang BISA DILIHAT oleh Laravel
        $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
        $output .= "<h3>Tabel yang terdeteksi oleh sistem Laravel saat ini:</h3><ul>";
        foreach ($tables as $table) {
            $tableName = json_decode(json_encode($table), true);
            $output .= "<li>" . implode(', ', $tableName) . "</li>";
        }
        $output .= "</ul>";

        return $output;

    } catch (\Exception $e) {
        return "<h1>Gagal!</h1><p>Error: " . $e->getMessage() . "</p>";
    }
});
