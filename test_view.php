<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$konsumen = App\Models\Konsumen::find(1);
$kecamatans = \App\Models\Kecamatan::orderBy('nama_kecamatan')->get();
try {
    $view = view('disperindag.konsumens.edit', compact('konsumen', 'kecamatans'))->render();
    echo "Render successful";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
