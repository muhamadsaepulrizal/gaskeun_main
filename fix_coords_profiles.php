<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$pangkalan = DB::table('pangkalan_profiles')->get();
$updatedCount = 0;

foreach ($pangkalan as $p) {
    $origLat = trim($p->latitude);
    $origLng = trim($p->longitude);
    
    $lat = $origLat;
    $lng = $origLng;

    // Fix Latitude
    if (strlen($lat) > 2 && !str_contains($lat, '.')) {
        $cleanLat = ltrim(str_replace('-', '', $lat), '0'); // remove - and leading zeros
        if (str_starts_with($cleanLat, '7')) {
            $lat = '-7.' . substr($cleanLat, 1);
        }
    }
    // Remove zero padding
    $lat = str_replace('-07.', '-7.', $lat);

    // Fix Longitude
    if (strlen($lng) > 3 && !str_contains($lng, '.')) {
        if (str_starts_with($lng, '107')) {
            $lng = '107.' . substr($lng, 3);
        } elseif (str_starts_with($lng, '108')) {
            $lng = '108.' . substr($lng, 3);
        }
    }

    // Fix completely wrong ones like 108.9... which is likely a typo for 107.9...
    if (str_starts_with($lng, '108.9')) {
        $lng = '107.9' . substr($lng, 5);
    }

    if ($lat !== $origLat || $lng !== $origLng) {
        DB::table('pangkalan_profiles')->where('id', $p->id)->update([
            'latitude' => $lat,
            'longitude' => $lng,
        ]);
        echo "Updated ID {$p->id}: ($origLat, $origLng) -> ($lat, $lng)\n";
        $updatedCount++;
    }
}

echo "Total updated: $updatedCount\n";
