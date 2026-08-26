<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$req = Illuminate\Http\Request::create('/pangkalan/konsumen/search', 'GET', ['kategori' => 'Nelayan Sasaran', 'q' => 'Andika']);
$controller = app()->make('App\Http\Controllers\PangkalanController');
echo json_encode($controller->searchKonsumen($req)->getData());
