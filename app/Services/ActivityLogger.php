<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(
        string $subjectType,
        int $subjectId,
        string $action,
        ?array $old = null,
        ?array $new = null,
        ?string $description = null
    ): void {
        ActivityLog::create([
            'subject_type' => $subjectType,
            'subject_id'   => $subjectId,
            'action'       => $action,
            'old_values'   => $old,
            'new_values'   => $new,
            'description'  => $description,
            'user_id'      => Auth::id(),
        ]);
    }
}
