<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            // drop FK lama jika ada dan salah target
            if (Schema::hasColumn('shipments', 'created_by')) {
                try {
                    $table->dropForeign(['created_by']);
                } catch (\Throwable $e) {
                    // FK mungkin belum pernah terpasang, abaikan
                }
            }
            $table->foreign('created_by')->references('id')->on('user');
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
        });
    }
};
