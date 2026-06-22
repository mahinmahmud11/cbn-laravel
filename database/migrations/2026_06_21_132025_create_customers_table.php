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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('province_id')->nullable();
            $table->string('regency_id')->nullable();
            $table->string('district_id')->nullable();
            $table->string('village_id')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->enum('type', ['sender', 'receiver', 'both'])->default('both');
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('user')->nullOnDelete();
            $table->timestamps();

            $table->index(['name', 'phone']); // untuk LIKE search auto-fill
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
