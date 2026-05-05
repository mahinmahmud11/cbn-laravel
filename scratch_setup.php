<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
$agentRole = Role::firstOrCreate(['name' => 'agent']);

// Setup Agent Test Account
$user = User::find(3);
if ($user) {
    $user->password_hash = Hash::make('agen123');
    $user->save();
    $user->assignRole($agentRole);
    echo "User 3 (Agent) updated. Email: " . $user->email . "\n";
}

// Make sure some user is super_admin for testing
// I'll pick ID 1 or current admin if I knew it. 
// Let's check user 1.
$admin = User::where('username', 'admin')->first() ?: User::first();
if ($admin) {
    $admin->assignRole($superAdmin);
    echo "Admin (" . $admin->username . ") assigned super_admin role.\n";
}
