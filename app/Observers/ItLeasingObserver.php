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

        // default action
        $action = 'updated';
        $description = 'IT Leasing updated';

        // kapag status ang binago
        if (isset($dirty['status'])) {
            match ($dirty['status']) {
                'returned'  => $action = 'returned',
                'in_repair' => $action = 'damaged',
                default     => $action = 'status_updated',
            };

            $description = 'IT Leasing status changed';
        }

        ActivityLogger::log(
            'ItLeasing',
            $leasing->id,
            $action,
            $old,
            $dirty,
            $description
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
