<?php

namespace App\Observers;

use App\Models\FixedAsset;
use App\Services\ActivityLogger;

class FixedAssetObserver
{
    /**
     * Auto log kapag CREATE
     */
    public function created(FixedAsset $asset): void
    {
        ActivityLogger::log(
            'FixedAsset',
            $asset->id,
            'created',
            null,
            $asset->toArray(),
            'Fixed asset item created'
        );
    }

    /**
     * Auto log kapag UPDATE
     */
    public function updating(FixedAsset $asset): void
    {
        $old = $asset->getOriginal();
        $dirty = $asset->getDirty();

        ActivityLogger::log(
            'FixedAsset',
            $asset->id,
            'updated',
            $old,
            $dirty,
            'Fixed asset item updated'
        );
    }

    /**
     * Auto log kapag DELETE
     */
    public function deleted(FixedAsset $asset): void
    {
        ActivityLogger::log(
            'FixedAsset',
            $asset->id,
            'deleted',
            $asset->toArray(),
            null,
            'Fixed asset item deleted'
        );
    }
}
