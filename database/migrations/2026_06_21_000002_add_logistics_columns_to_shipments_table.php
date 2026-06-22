<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom detail logistik ke tabel shipments.
     *
     * Tabel shipments sebelumnya hanya punya kolom identitas dasar.
     * Kolom-kolom ini diperlukan sesuai PRD Section 8.1 agar tabel ini
     * bisa menjadi single source of truth untuk transaksi resi baru
     * (menggantikan peran ship_items yang dibekukan sebagai arsip).
     *
     * Kolom lama (tracking_number, customer_name, recipient_name, dll)
     * TIDAK diubah — hanya penambahan.
     */
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            // Pengirim
            $table->string('sender_phone', 20)->nullable()->after('customer_name');

            // Penerima (recipient_name & recipient_phone sudah ada)

            // Rute berbasis distrik (FK ke tabel geography legacy, nullable agar backward-compatible)
            $table->unsignedBigInteger('origin_district_id')->nullable()->after('destination_city');
            $table->unsignedBigInteger('destination_district_id')->nullable()->after('origin_district_id');

            // Detail paket
            $table->unsignedInteger('pieces')->default(1)->after('destination_district_id');
            $table->decimal('weight_kg', 8, 2)->nullable()->after('pieces');
            $table->string('dimension', 20)->nullable()->after('weight_kg')->comment('Format: PxLxT, contoh: 30x20x15');
            $table->enum('package_type', ['regular', 'ons', 'sds', 'is'])->default('regular')->after('dimension');
            $table->text('package_content')->nullable()->after('package_type');

            // Finansial
            $table->decimal('price', 12, 2)->nullable()->after('package_content');

            // Catatan & metadata
            $table->text('notes')->nullable()->after('price');

            // Index untuk kolom yang akan sering difilter
            $table->index('origin_district_id');
            $table->index('destination_district_id');
            $table->index('current_status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropIndex(['origin_district_id']);
            $table->dropIndex(['destination_district_id']);
            $table->dropIndex(['current_status']);
            $table->dropIndex(['created_at']);

            $table->dropColumn([
                'sender_phone',
                'origin_district_id',
                'destination_district_id',
                'pieces',
                'weight_kg',
                'dimension',
                'package_type',
                'package_content',
                'price',
                'notes',
            ]);
        });
    }
};
