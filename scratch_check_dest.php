<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$dest = DB::table('lp_destination')->first();
echo "DESTINATION DATA:\n";
print_r($dest);
