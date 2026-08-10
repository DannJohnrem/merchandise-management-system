<?php

namespace App\Observers;

use App\Models\ItLeasing;
use App\Models\ItLeasingStatusHistory;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Auth;

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

        // Log the initial status into the dedicated status-history table
        ItLeasingStatusHistory::create([
            'it_leasing_id' => $leasing->id,
            'changed_by' => Auth::id(),
            'from_status' => null,
            'to_status' => $leasing->status,
            'remarks' => 'Item created.',
            'changed_at' => now(),
        ]);
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

        // If the status field specifically changed, also log it into the
        // dedicated status-history table for the per-item timeline view.
        if (array_key_exists('status', $changes)) {
            ItLeasingStatusHistory::create([
                'it_leasing_id' => $leasing->id,
                'changed_by' => Auth::id(),
                'from_status' => $old['status'] ?? null,
                'to_status' => $changes['status'],
                'remarks' => null,
                'changed_at' => now(),
            ]);
        }
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
