<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel surat_jalan.
     *
     * Surat Jalan = dokumen pengantar yang diberikan ke kurir/driver saat
     * mengantarkan paket ke penerima. Berbeda dengan Manifest (dokumen
     * pengiriman antar-cabang), Surat Jalan adalah untuk pengiriman last-mile.
     */
    public function up(): void
    {
        Schema::create('surat_jalan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor', 30)->unique()->comment('Nomor surat jalan, format: SJ-YYMM-NNNNN');
            $table->foreignId('armada_id')->nullable()->constrained('armada')->nullOnDelete();
            $table->string('driver_name', 100);
            $table->string('driver_phone', 20)->nullable();
            $table->date('tanggal_kirim');
            $table->enum('status', ['draft', 'dikirim', 'selesai', 'gagal'])->default('draft');
            $table->text('catatan')->nullable();
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('user')->restrictOnDelete();
            $table->timestamps();
        });

        // Pivot: surat_jalan <-> shipments (many-to-many)
        Schema::create('surat_jalan_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_jalan_id')->constrained('surat_jalan')->cascadeOnDelete();
            $table->foreignId('shipment_id')->constrained('shipments')->cascadeOnDelete();
            $table->unique(['surat_jalan_id', 'shipment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_jalan_shipments');
        Schema::dropIfExists('surat_jalan');
    }
};
