<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel counter AWB yang aman dari race condition.
     * Setiap baris mewakili satu periode bulan (format: YYMM, contoh: 2606).
     * last_sequence dikunci dengan lockForUpdate() saat dibaca, bukan dihitung
     * dari tabel transaksi — ini mencegah dua request bersamaan mendapat nomor yang sama.
     */
    public function up(): void
    {
        Schema::create('awb_sequences', function (Blueprint $table) {
            $table->id();
            $table->string('period', 4)->unique()->comment('Format YYMM, contoh: 2606 untuk Juni 2026');
            $table->unsignedInteger('last_sequence')->default(0)->comment('Nomor urut terakhir yang sudah digunakan pada periode ini');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('awb_sequences');
    }
};
