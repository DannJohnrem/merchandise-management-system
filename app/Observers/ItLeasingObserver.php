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
            'ItLeasing',
            $leasing->id,
            'created',
            null,
            $leasing->toArray(),
            'IT Leasing item created'
        );
    }

    /**
     * Auto log kapag UPDATE (status-aware)
     */
    public function updating(ItLeasing $leasing): void
    {
        $old = $leasing->getOriginal();
        $dirty = $leasing->getDirty();

        ActivityLogger::log(
            'ItLeasing',
            $leasing->id,
            'updated',
            $old,
            $dirty,
            'IT Leasing item updated'
        );
    }

    /**
     * Auto log kapag DELETE
     */
    public function deleted(ItLeasing $leasing): void
    {
        ActivityLogger::log(
            'ItLeasing',
            $leasing->id,
            'deleted',
            $leasing->toArray(),
            null,
            'IT Leasing item deleted'
        );
    }
}
