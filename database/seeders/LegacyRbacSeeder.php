<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class LegacyRbacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Force Reset Password for ID 1 or the first Active Super Admin
        $superAdmin = User::where('id', 1)->first();
        if (!$superAdmin) {
            $superAdmin = User::where('status', 10)->first();
        }

        if ($superAdmin) {
            $superAdmin->password_hash = Hash::make('password123');
            $superAdmin->save();
            $this->command->info('----------------------------------------------------');
            $this->command->info('DEVELOPER BACKDOOR CREDENTIALS:');
            $this->command->info('Username : ' . $superAdmin->username);
            $this->command->info('Email    : ' . $superAdmin->email);
            $this->command->info('Password : password123');
            $this->command->info('----------------------------------------------------');
        } else {
            $this->command->error('Tidak ditemukan user aktif untuk di-reset!');
        }

        // 2. Migrate Roles
        $this->command->info('Memigrasikan data RBAC dari legacy Yii2...');
        
        try {
            // Mencoba membaca tabel bawaan Yii2 (auth_assignment / tbl_auth_assignment)
            $tableName = 'auth_assignment';
            if (!DB::getSchemaBuilder()->hasTable($tableName)) {
                $tableName = 'tbl_auth_assignment';
            }

            if (DB::getSchemaBuilder()->hasTable($tableName)) {
                $assignments = DB::table($tableName)->get();

                foreach ($assignments as $assign) {
                    // Buat role jika belum ada di tabel Spatie
                    $role = Role::firstOrCreate([
                        'name' => $assign->item_name,
                        'guard_name' => 'web'
                    ]);

                    // Assign ke user terkait
                    $user = User::find($assign->user_id);
                    if ($user) {
                        $user->assignRole($role);
                    }
                }
                $this->command->info('Migrasi RBAC Legacy ke Spatie selesai!');
            } else {
                throw new \Exception('Tabel Yii2 RBAC tidak ditemukan.');
            }

        } catch (\Exception $e) {
            $this->command->warn('Gagal migrasi otomatis: ' . $e->getMessage());
            $this->command->info('Fallback: Mendaftarkan role default (Super Admin) dan assign ke user pertama.');
            
            $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
            if ($superAdmin) {
                $superAdmin->assignRole($role);
            }
        }
    }
}
