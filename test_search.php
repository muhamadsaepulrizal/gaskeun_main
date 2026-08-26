<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$kategori = 'Usaha Mikro';
$keyword = 'Agus';
$hashKeyword = hash('sha256', $keyword);

$query = App\Models\Konsumen::where('kategori', $kategori)->where(function($q) use ($keyword, $hashKeyword) {
    $q->where('nama_lengkap', 'like', "%{$keyword}%")
      ->orWhere('nik_hash', $hashKeyword);
});

$konsumens = $query->limit(10)->get()->map(function($k) {
    $identifier = $k->nik;
    return [
        'id' => $k->id,
        'nama' => $k->nama_lengkap,
        'identifier' => $identifier ? substr($identifier, 0, 6) . str_repeat('*', max(0, strlen($identifier) - 6)) : '-',
        'alamat' => $k->alamat
    ];
});

echo json_encode($konsumens);
