<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;

class AssetTagGenerator
{
    public static function generate(?string $category = null): string
    {
        $date = Carbon::now()->format('Ymd');
        $random = strtoupper(Str::random(8));

        // Convert category to short code
        // Laptop -> LAP, Desktop -> DES, Monitor -> MON, Others -> OTH
        $code = self::categoryCode($category);

        return "FA-{$code}-{$date}-{$random}";
    }

    protected static function categoryCode(?string $category): string
    {
        if (!$category) {
            return 'GEN'; // generic if no category yet
        }

        $map = [
            'Laptop'    => 'LAP',
            'Desktop'   => 'DES',
            'Monitor'   => 'MON',
            'Printer'   => 'PRN',
            'Scanner'   => 'SCN',
            'Furniture' => 'FUR',
            'Equipment' => 'EQU',
            'Others'    => 'OTH',
        ];

        return $map[$category] ?? strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $category), 0, 3));
    }
}
