<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SmartMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:smart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations smartly, skipping tables/columns that already exist without crashing.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai Smart Migrate...');
        Artisan::call('migrate:install'); // Pastikan tabel migrasi ada
        
        $files = glob(database_path('migrations/*.php'));
        $batch = DB::table('migrations')->max('batch') + 1;

        foreach ($files as $file) {
            $migration = basename($file, '.php');
            $exists = DB::table('migrations')->where('migration', $migration)->exists();
            
            if (!$exists) {
                try {
                    Artisan::call('migrate', [
                        '--path' => 'database/migrations/' . basename($file), 
                        '--force' => true
                    ]);
                    $this->info("✅ Berhasil migrate: {$migration}");
                } catch (\Exception $e) {
                    $msg = $e->getMessage();
                    if (str_contains($msg, 'already exists') || str_contains($msg, 'Duplicate column name') || str_contains($msg, 'Duplicate key name')) {
                        DB::table('migrations')->insert([
                            'migration' => $migration,
                            'batch' => $batch
                        ]);
                        $this->warn("⚠️ Dilewati (Sudah ada di DB), ditandai selesai: {$migration}");
                    } else {
                        $this->error("❌ Gagal pada {$migration}: " . $msg);
                        return Command::FAILURE;
                    }
                }
            }
        }

        $this->info('Smart Migrate selesai!');
        return Command::SUCCESS;
    }
}
