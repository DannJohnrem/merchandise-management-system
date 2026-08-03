<?php

namespace App\Observers;

use App\Support\InventoryCache;

class InventoryDataObserver
{
    public function saved($model): void
    {
        InventoryCache::bumpVersion();
    }

    public function deleted($model): void
    {
        InventoryCache::bumpVersion();
    }

    public function restored($model): void
    {
        InventoryCache::bumpVersion();
    }
}
