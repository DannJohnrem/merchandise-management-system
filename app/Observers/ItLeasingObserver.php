<?php

namespace App\Observers;

use App\Models\ItLeasing;
use App\Services\ActivityLogger;

class ItLeasingObserver
{
    /**
     * Auto log kapag CREATE
     */
    public function created(ItLeasing $leasing): void
    {
        ActivityLogger::log(
            subjectType: ItLeasing::class,
            subjectId: $leasing->id,
            action: 'created',
            old: null,
            new: $leasing->getAttributes(),
            description: 'IT Leasing item created'
        );
    }

    /**
     * ✅ Use updated() not updating()
     * - ensures DB update succeeded
     * - getChanges() returns only modified fields
     */
    public function updated(ItLeasing $leasing): void
    {
        $old = $leasing->getOriginal();
        $changes = $leasing->getChanges();

        if (empty($changes)) {
            return;
        }

        ActivityLogger::log(
            subjectType: ItLeasing::class,
            subjectId: $leasing->id,
            action: 'updated',
            old: $old,
            new: $changes,
            description: 'IT Leasing item updated'
        );
    }

    /**
     * Auto log kapag DELETE
     */
    public function deleted(ItLeasing $leasing): void
    {
        ActivityLogger::log(
            subjectType: ItLeasing::class,
            subjectId: $leasing->id,
            action: 'deleted',
            old: $leasing->getOriginal(),
            new: null,
            description: 'IT Leasing item deleted'
        );
    }
}
