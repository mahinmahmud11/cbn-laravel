<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel armada (kendaraan) baru.
     *
     * Tabel ini tidak ada di legacy Yii2 — armada sebelumnya dicatat manual
     * sebagai string di kolom vehicle_plate & driver_name di manifests.
     * Tabel ini menjadi master data armada untuk Sprint 2+.
     */
    public function up(): void
    {
        Schema::create('armada', function (Blueprint $table) {
            $table->id();
            $table->string('nopol', 20)->unique()->comment('Nomor polisi kendaraan, misal: B 1234 ABC');
            $table->string('jenis', 50)->comment('Jenis kendaraan: motor, pickup, truk, dll');
            $table->string('merek', 50)->nullable()->comment('Merek kendaraan: Honda, Toyota, dll');
            $table->string('warna', 30)->nullable();
            $table->year('tahun')->nullable()->comment('Tahun pembuatan');
            $table->enum('status', ['aktif', 'maintenance', 'nonaktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('armada');
    }
};
