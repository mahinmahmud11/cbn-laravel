<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tariff = DB::table('lp_tariff')->first();
echo "TARIFF DATA:\n";
print_r($tariff);
