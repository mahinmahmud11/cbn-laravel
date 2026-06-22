<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('manifest_items', function (Blueprint $table) {
            $table->unsignedBigInteger('ship_item_id')->nullable()->change();
            $table->foreignId('shipment_id')->nullable()->after('ship_item_id')->constrained('shipments')->cascadeOnDelete();
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->unsignedBigInteger('ship_item_id')->nullable()->change();
            $table->foreignId('shipment_id')->nullable()->after('ship_item_id')->constrained('shipments')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manifest_items', function (Blueprint $table) {
            $table->dropForeign(['shipment_id']);
            $table->dropColumn('shipment_id');
            $table->unsignedBigInteger('ship_item_id')->nullable(false)->change();
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropForeign(['shipment_id']);
            $table->dropColumn('shipment_id');
            $table->unsignedBigInteger('ship_item_id')->nullable(false)->change();
        });
    }
};
