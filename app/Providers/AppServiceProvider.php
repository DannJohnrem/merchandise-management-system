<?php

namespace App\Providers;

use App\Models\ItLeasing;
use App\Models\FixedAsset;
use App\Observers\ItLeasingObserver;
use App\Observers\FixedAssetObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ItLeasing::observe(ItLeasingObserver::class);
        FixedAsset::observe(FixedAssetObserver::class);
    }
}
