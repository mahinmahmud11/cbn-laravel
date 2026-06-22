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
        Schema::table('shipments', function (Blueprint $table) {
            // --- Address Book & Snapshot ---
            $table->unsignedBigInteger('customer_id')->nullable()->after('created_by');
            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
            
            $table->string('sender_name')->nullable()->after('customer_name');
            $table->text('sender_address')->nullable()->after('sender_name');
            $table->string('origin_province_id')->nullable()->after('origin_city');
            $table->string('origin_regency_id')->nullable()->after('origin_province_id');
            $table->string('origin_village_id')->nullable()->after('origin_district_id');
            $table->string('origin_postal_code', 10)->nullable()->after('origin_village_id');
            
            $table->text('receiver_address')->nullable()->after('recipient_phone');
            $table->string('destination_province_id')->nullable()->after('destination_city');
            $table->string('destination_regency_id')->nullable()->after('destination_province_id');
            $table->string('destination_village_id')->nullable()->after('destination_district_id');
            $table->string('destination_postal_code', 10)->nullable()->after('destination_village_id');
            
            // --- Pricing & Package Details ---
            $table->boolean('is_fragile')->default(false)->after('package_content');
            $table->decimal('base_price', 15, 2)->nullable()->after('price'); // Harga dasar ongkir
            $table->decimal('packing_fee', 15, 2)->default(0)->after('base_price');
            $table->decimal('insurance_fee', 15, 2)->default(0)->after('packing_fee');
            $table->decimal('discount_amount', 15, 2)->default(0)->after('insurance_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn([
                'customer_id',
                'sender_name',
                'sender_address',
                'origin_province_id',
                'origin_regency_id',
                'origin_village_id',
                'origin_postal_code',
                'receiver_address',
                'destination_province_id',
                'destination_regency_id',
                'destination_village_id',
                'destination_postal_code',
                'is_fragile',
                'base_price',
                'packing_fee',
                'insurance_fee',
                'discount_amount'
            ]);
        });
    }
};
