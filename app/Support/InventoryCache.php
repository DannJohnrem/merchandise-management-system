<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class InventoryCache
{
    protected const VERSION_KEY = 'inventory_cache_version';

    public static function version(): int
    {
        if (! Cache::has(self::VERSION_KEY)) {
            Cache::forever(self::VERSION_KEY, 1);
        }

        return (int) Cache::get(self::VERSION_KEY, 1);
    }

    public static function bumpVersion(): void
    {
        if (! Cache::has(self::VERSION_KEY)) {
            Cache::forever(self::VERSION_KEY, 1);

            return;
        }

        Cache::increment(self::VERSION_KEY);
    }

    public static function unitsKey(string $source, string $itemKey): string
    {
        return sprintf(
            'inventory_units:v%d:%s:%s',
            self::version(),
            $source,
            md5($itemKey)
        );
    }
}
