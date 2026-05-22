<?php

namespace App\Observers;

use App\Models\FixedAsset;
use App\Services\ActivityLogger;
use App\Services\AssetTagGenerator;

class FixedAssetObserver
{
    /**
     * 🔹 BEFORE save to DB
     * Auto-generate asset tag (no duplicates)
     */
    public function creating(FixedAsset $asset): void
    {
        // If user already provided an asset_tag, keep it
        if (!empty($asset->asset_tag)) {
            return;
        }

        $category = $asset->category; // used for FA-LAP-...

        do {
            $tag = AssetTagGenerator::generate($category);
            $exists = FixedAsset::where('asset_tag', $tag)->exists();
        } while ($exists);

        $asset->asset_tag = $tag;
    }

    /**
     * 🔹 AFTER save to DB
     * Activity log for CREATE
     */
    public function created(FixedAsset $asset): void
    {
        ActivityLogger::log(
            subjectType: FixedAsset::class,
            subjectId: $asset->id,
            action: 'created',
            old: null,
            new: $asset->getAttributes(),
            description: 'Fixed asset item created'
        );
    }

    /**
     * 🔹 AFTER update
     * Activity log for UPDATE
     */
    public function updated(FixedAsset $asset): void
    {
        $old = $asset->getOriginal();
        $changes = $asset->getChanges();

        if (empty($changes)) {
            return;
        }

        ActivityLogger::log(
            subjectType: FixedAsset::class,
            subjectId: $asset->id,
            action: 'updated',
            old: $old,
            new: $changes,
            description: 'Fixed asset item updated'
        );
    }

    /**
     * 🔹 AFTER delete
     * Activity log for DELETE
     */
    public function deleted(FixedAsset $asset): void
    {
        ActivityLogger::log(
            subjectType: FixedAsset::class,
            subjectId: $asset->id,
            action: 'deleted',
            old: $asset->getOriginal(),
            new: null,
            description: 'Fixed asset item deleted'
        );
    }
}
