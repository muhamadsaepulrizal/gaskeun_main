<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$sql = file_get_contents(__DIR__.'/pangkalan.sql');
preg_match_all('/INSERT INTO `pangkalan` .*? VALUES\s*(.*?);/is', $sql, $matches);

$count = 0;

foreach ($matches[1] as $valuesStr) {
    // Split by ),( to get individual rows
    // Since some fields might have commas inside quotes, let's use a simpler approach or regex
    preg_match_all('/\((.*?)\)/', $valuesStr, $rows);
    
    foreach ($rows[1] as $row) {
        $cols = str_getcsv($row, ",", "'");
        
        if (count($cols) >= 10) {
            $nama = trim($cols[1]); // 'pangkalan'
            $rawLat = trim($cols[8]); // 'latitude'
            $rawLng = trim($cols[9]); // 'longitude'
            
            // Clean up the lat/lng like we did before
            $lat = str_replace([' ', "'"], '', $rawLat);
            $lng = str_replace([' ', "'"], '', $rawLng);

            // Fix Latitude
            if (strlen($lat) > 2 && !str_contains($lat, '.')) {
                $cleanLat = ltrim(str_replace('-', '', $lat), '0');
                if (str_starts_with($cleanLat, '7')) {
                    $lat = '-7.' . substr($cleanLat, 1);
                }
            }
            $lat = str_replace('-07.', '-7.', $lat);
            if (!str_starts_with($lat, '-') && str_starts_with($lat, '7')) {
                $lat = '-' . $lat;
            }

            // Fix Longitude
            if (strlen($lng) > 3 && !str_contains($lng, '.')) {
                if (str_starts_with($lng, '107')) {
                    $lng = '107.' . substr($lng, 3);
                } elseif (str_starts_with($lng, '108')) {
                    $lng = '108.' . substr($lng, 3);
                }
            }
            if (str_starts_with($lng, '108.9')) {
                $lng = '107.9' . substr($lng, 5);
            }

            // Ensure valid float
            $lat = (float) $lat;
            $lng = (float) $lng;

            if ($lat != 0 && $lng != 0 && $lat >= -8 && $lat <= -6 && $lng >= 107 && $lng <= 109) {
                // Update based on nama_pangkalan
                $affected = DB::table('pangkalan_profiles')
                    ->where('nama_pangkalan', $nama)
                    ->update([
                        'latitude' => $lat,
                        'longitude' => $lng
                    ]);
                if ($affected) {
                    $count++;
                }
            }
        }
    }
}

echo "Successfully recovered and updated $count coordinates from pangkalan.sql\n";
