<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('shipment_pods')) {
            return;
        }
        Schema::create('shipment_pods', function (Blueprint $table) {
            $table->id();

            // HANYA buat kolomnya saja untuk menyimpan ID dari tabel legacy.
            // Constraint foreign key dihapus agar migrasi tidak terblokir (Error 150).
            $table->unsignedInteger('ship_status_id');

            $table->string('file_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_pods');
    }
};