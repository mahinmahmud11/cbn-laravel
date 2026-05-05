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
        Schema::create('manifests', function (Blueprint $table) {
            $table->id();
            $table->string('manifest_number')->unique();
            $table->foreignId('origin_agency_id')->constrained('agencies');
            $table->foreignId('destination_agency_id')->constrained('agencies');
            $table->string('driver_name')->nullable();
            $table->string('vehicle_plate')->nullable();
            $table->enum('status', ['draft', 'transit', 'arrived', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manifests');
    }
};
