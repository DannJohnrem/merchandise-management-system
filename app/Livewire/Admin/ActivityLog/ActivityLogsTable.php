<?php

namespace App\Livewire\Admin\ActivityLog;

use App\Models\ActivityLog;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ActivityLogsTable extends DataTableComponent
{
    protected $model = ActivityLog::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
             ->setDefaultSort('created_at', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Module', 'subject_type')->sortable(),
            Column::make('Item ID', 'subject_id')->sortable(),
            Column::make('Action', 'action')
                ->format(fn($value) => ucfirst($value))
                ->sortable(),
            Column::make('Description', 'description')->collapseOnTablet(),

            // Decode old_values JSON and display nicely
            Column::make('Old Values', 'old_values')
                ->format(function($value) {
                    if (!$value) return '-';
                    $data = is_array($value) ? $value : json_decode($value, true);
                    if (!$data) return '-';

                    $html = '<ul class="text-xs space-y-1">';
                    foreach ($data as $key => $val) {
                        $html .= '<li><strong>' . $key . ':</strong> ' . (is_array($val) ? json_encode($val) : $val) . '</li>';
                    }
                    $html .= '</ul>';
                    return $html;
                })
                ->html()
                ->collapseOnTablet(),

            // Decode new_values JSON and display nicely
            Column::make('New Values', 'new_values')
                ->format(function($value) {
                    if (!$value) return '-';
                    $data = is_array($value) ? $value : json_decode($value, true);
                    if (!$data) return '-';

                    $html = '<ul class="text-xs space-y-1">';
                    foreach ($data as $key => $val) {
                        $html .= '<li><strong>' . $key . ':</strong> ' . (is_array($val) ? json_encode($val) : $val) . '</li>';
                    }
                    $html .= '</ul>';
                    return $html;
                })
                ->html()
                ->collapseOnTablet(),

            Column::make('User', 'user.name')
                ->format(fn($value) => $value ?? 'System')
                ->sortable(),

            Column::make('Created At', 'created_at')
                ->format(fn($value) => $value->format('Y-m-d H:i'))
                ->sortable(),
        ];
    }
}
