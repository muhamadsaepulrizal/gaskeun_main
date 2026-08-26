<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$k = App\Models\Konsumen::all();
foreach ($k as $c) {
    echo $c->id . " | " . $c->kategori . " | " . $c->nama_lengkap . "\n";
}
