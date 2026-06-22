<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // STEP 1 — tambah kolom baru dulu. JANGAN drop 'status' sebelum backfill selesai.
        // CATATAN: 'due_date' TIDAK ditambahkan di sini karena sudah ada (lihat hasil
        // audit Rule 18 — SHOW COLUMNS FROM invoices, kolom due_date sudah eksis).
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('id')->constrained('companies')->nullOnDelete();
            $table->decimal('paid_amount', 15, 2)->default(0.00)->after('total_amount');
            $table->enum('billing_status', [
                'belum_ditagih',
                'sudah_ditagih',
                'sebagian_dibayar',
                'lunas',
                'dibatalkan', // ditambahkan agar kapabilitas 'cancelled' di kolom lama tidak hilang
            ])->default('belum_ditagih')->after('paid_amount');

            $table->index(['billing_status', 'due_date']); // untuk default sort InvoiceResource
            $table->index(['company_id', 'billing_status']); // untuk widget OutstandingInvoicesByCompany
        });

        // STEP 2 — backfill billing_status dari kolom status lama.
        DB::table('invoices')->where('status', 'pending')->update(['billing_status' => 'sudah_ditagih']);
        DB::table('invoices')->where('status', 'paid')->update(['billing_status' => 'lunas']);
        DB::statement('UPDATE invoices SET paid_amount = total_amount WHERE status = "paid"');
        DB::table('invoices')->where('status', 'cancelled')->update(['billing_status' => 'dibatalkan']);

        // STEP 3 — setelah backfill aman, drop kolom status lama agar tidak ada dua sumber kebenaran.
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending')->after('total_amount');
        });

        DB::table('invoices')->where('billing_status', 'lunas')->update(['status' => 'paid']);
        DB::table('invoices')->where('billing_status', 'dibatalkan')->update(['status' => 'cancelled']);
        DB::table('invoices')
            ->whereIn('billing_status', ['belum_ditagih', 'sudah_ditagih', 'sebagian_dibayar'])
            ->update(['status' => 'pending']);

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['invoices_billing_status_due_date_index']);
            $table->dropIndex(['invoices_company_id_billing_status_index']);
            $table->dropColumn(['paid_amount', 'billing_status']);
            $table->dropConstrainedForeignId('company_id');
        });
    }
};
