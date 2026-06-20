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
        Schema::create('pickups', function (Blueprint $table) {
            $table->id();
            $table->string('pickup_number')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('pickup_address');
            $table->dateTime('pickup_time');
            $table->integer('estimated_items')->default(1);
            $table->enum('status', ['pending', 'assigned', 'picked_up', 'cancelled'])->default('pending');
            $table->unsignedBigInteger('courier_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickups');
    }
};
