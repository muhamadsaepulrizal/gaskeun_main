<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$pangkalans = DB::table('pangkalan')->get();
$count = 0;

foreach ($pangkalans as $p) {
    if (!$p->latitude || !$p->longitude) continue;

    $lat = (float) $p->latitude;
    $lng = (float) $p->longitude;

    // If swapped, swap them back
    if ($lat > 100 && $lng < 0) {
        $temp = $lat;
        $lat = $lng;
        $lng = $temp;
    }

    // Only update if they are within valid bounds for Garut (~ -7, 107)
    if ($lat >= -10 && $lat <= -6 && $lng >= 100 && $lng <= 120) {
        $affected = DB::table('pangkalan_profiles')
            ->where('nama_pangkalan', $p->pangkalan)
            ->update([
                'latitude' => $lat,
                'longitude' => $lng
            ]);
        if ($affected) {
            $count++;
        }
    }
}

echo "Safely synced $count coordinates!\n";
