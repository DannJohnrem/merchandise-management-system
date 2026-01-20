<?php

namespace App\Livewire\Admin\ActivityLog;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ActivityLogsTable extends DataTableComponent
{
    public string $tableName = 'activity-logs-table';
    protected $model = ActivityLog::class;

    public function builder(): Builder
    {
        return ActivityLog::query()
            ->select([
                'id',
                'subject_type',
                'subject_id',
                'action',
                'description',
                'old_values',
                'new_values',
                'user_id',
                'created_at',
            ])
            ->with(['user:id,name']);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('created_at', 'desc')
            ->setPerPage(10)
            ->setPerPageAccepted([10, 25, 50, 100])
            ->setSearchDebounce(400)
            ->setColumnSelectDisabled()
            ->setEmptyMessage('No activity logs found.');
    }

    protected function normalizeForHash($value): string
    {
        if (is_array($value) || is_object($value)) return json_encode($value);
        if ($value === null) return '';
        return (string) $value;
    }

    /**
     * Cached rendering for JSON columns
     */
    protected function renderChangesListCached($value): string
    {
        if (empty($value)) return '-';

        if (is_array($value)) {
            return $this->renderList($value);
        }

        $hash = md5($this->normalizeForHash($value));
        $key  = "activity_log_html_full:{$hash}";

        return Cache::remember($key, now()->addMinutes(10), function () use ($value) {
            $data = json_decode((string) $value, true);
            if (!is_array($data) || empty($data)) return '-';
            return $this->renderList($data);
        });
    }

    /**
     * FULL DISPLAY — NO truncation, NO limits
     */
    protected function renderList(array $data): string
    {
        if (empty($data)) return '-';

        $ignoreKeys = ['created_at', 'updated_at', 'deleted_at', 'password', 'remember_token'];
        foreach ($ignoreKeys as $k) {
            unset($data[$k]);
        }

        if (empty($data)) {
            return '-';
        }

        $items = [];

        foreach ($data as $key => $val) {
            // Convert complex values safely
            if (is_array($val) || is_object($val)) {
                $val = json_encode($val, JSON_UNESCAPED_UNICODE);
            } elseif ($val === null) {
                $val = 'null';
            } elseif (is_bool($val)) {
                $val = $val ? 'true' : 'false';
            }

            $items[] =
                '<li class="break-all">' .
                    '<strong>' . e((string) $key) . ':</strong> ' .
                    e((string) $val) .
                '</li>';
        }

        return '<ul class="text-xs space-y-1">' . implode('', $items) . '</ul>';
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),

            Column::make('Module', 'subject_type')
                ->format(fn ($v) => class_basename((string) $v))
                ->sortable(),

            Column::make('Item ID', 'subject_id')->sortable(),

            Column::make('Action', 'action')
                ->format(fn ($v) => ucfirst((string) $v))
                ->sortable(),

            Column::make('Description', 'description')->collapseOnTablet(),

            Column::make('Old Values', 'old_values')
                ->format(fn ($v) => $this->renderChangesListCached($v))
                ->html()
                ->collapseOnTablet(),

            Column::make('New Values', 'new_values')
                ->format(fn ($v) => $this->renderChangesListCached($v))
                ->html()
                ->collapseOnTablet(),

            Column::make('User')
                ->label(fn ($row) => $row->user?->name ?? 'System')
                ->sortable(fn (Builder $q, string $dir) => $q->orderBy('user_id', $dir)),

            Column::make('Created At', 'created_at')
                ->format(fn ($v) => $v?->format('Y-m-d H:i'))
                ->sortable(),
        ];
    }
}
