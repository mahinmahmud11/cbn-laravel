<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('npwp', 25)->nullable()->unique();
            $table->text('billing_address');
            $table->string('pic_name')->nullable();
            $table->string('pic_phone', 20)->nullable();
            $table->unsignedSmallInteger('payment_term_days')->default(30);

            // Tabel legacy 'user' (singular) memakai PK int unsigned, BUKAN bigint unsigned.
            // Jangan pakai foreignId()/constrained() default — type mismatch ditolak MySQL (errno 150).
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('user')->nullOnDelete();

            $table->timestamps();
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
