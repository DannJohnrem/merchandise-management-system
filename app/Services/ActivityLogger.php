<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    protected static array $ignoreKeys = [
        'created_at',
        'updated_at',
        'deleted_at',
        'password',
        'remember_token',
    ];

    public static function log(
        string $subjectType,
        int $subjectId,
        string $action,
        ?array $old = null,
        ?array $new = null,
        ?string $description = null
    ): void {
        $action = strtolower($action);

        $old = is_array($old) ? self::stripIgnoredKeys($old) : [];
        $new = is_array($new) ? self::stripIgnoredKeys($new) : [];

        // ✅ When updated: store only changed keys
        if ($action === 'updated') {
            [$old, $new] = self::onlyChanged($old, $new);
        }

        ActivityLog::create([
            'subject_type' => $subjectType,
            'subject_id'   => $subjectId,
            'action'       => $action,
            'old_values'   => !empty($old) ? $old : null,
            'new_values'   => !empty($new) ? $new : null,
            'description'  => $description,
            'user_id'      => Auth::id(),
        ]);
    }

    protected static function stripIgnoredKeys(array $data): array
    {
        foreach (self::$ignoreKeys as $k) {
            unset($data[$k]);
        }
        return $data;
    }

    protected static function onlyChanged(array $old, array $new): array
    {
        // ✅ Only check keys that exist in $new (because $new is getChanges() / delta)
        $keys = array_keys($new);

        $oldDiff = [];
        $newDiff = [];

        foreach ($keys as $key) {
            $oldVal = $old[$key] ?? null;
            $newVal = $new[$key];

            if (!self::valuesEqual($oldVal, $newVal)) {
                $oldDiff[$key] = self::humanizeValue($oldVal);
                $newDiff[$key] = self::humanizeValue($newVal);
            }
        }

        return [self::stripIgnoredKeys($oldDiff), self::stripIgnoredKeys($newDiff)];
    }

    protected static function valuesEqual($a, $b): bool
    {
        $aNorm = (is_array($a) || is_object($a)) ? json_encode($a) : $a;
        $bNorm = (is_array($b) || is_object($b)) ? json_encode($b) : $b;

        return $aNorm === $bNorm;
    }

    protected static function humanizeValue($value)
    {
        if (is_null($value)) {
            return 'Not set';
        }

        if ($value === '') {
            return 'Empty';
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        return $value;
    }
}
