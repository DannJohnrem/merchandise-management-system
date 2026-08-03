<?php

namespace App\Livewire\Tables;

use App\Models\InventoryReportRow;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class InventoryListTable extends DataTableComponent
{
    protected $listeners = ['refreshInventoryTable' => '$refresh'];

    public function configure(): void
    {
        $this->setPrimaryKey('row_id');
        $this->setPerPage(25);
        $this->setPerPageAccepted([10, 25, 50, 100]);
        $this->setAdditionalSelects(['row_id', 'item_key']);

        $this->setTrAttributes(function ($row) {
            $class = 'hover:bg-zinc-50 dark:hover:bg-zinc-700';

            if ($row->source === 'it_leasing_inclusion') {
                $class .= ' tr-inclusion-row';
            }

            return ['class' => $class];
        });
    }

    public function builder(): Builder
    {
        $query = InventoryReportRow::queryUnion();

        $dateFilter = $this->getAppliedFilterWithValue('purchase_date');
        if ($dateFilter) {
            $start = $dateFilter['minDate'] ?? null;
            $end = $dateFilter['maxDate'] ?? null;

            if (! empty($start)) {
                $query->whereDate('purchase_date', '>=', $start);
            }
            if (! empty($end)) {
                $query->whereDate('purchase_date', '<=', $end);
            }
        }

        $modelFilter = $this->getAppliedFilterWithValue('model');
        if (! empty($modelFilter)) {
            $query->where('model', $modelFilter);
        }

        return $query;
    }

    public function filters(): array
    {
        return [
            'purchase_date' => DateRangeFilter::make('Purchase Date')
                ->config([
                    'ranges' => [
                        'Today' => [now(), now()],
                        'Last 7 Days' => [now()->subDays(6), now()],
                        'Last 30 Days' => [now()->subDays(29), now()],
                        'This Month' => [now()->startOfMonth(), now()->endOfMonth()],
                        'Last Month' => [now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()],
                    ],
                ]),

            'model' => SelectFilter::make('Model')
                ->options(
                    InventoryReportRow::queryUnion()
                        ->select('model')
                        ->distinct()
                        ->whereNotNull('model')
                        ->orderBy('model')
                        ->pluck('model', 'model')
                        ->toArray()
                ),
        ];
    }

    public function columns(): array
    {
        $click = function ($row) {
            $payload = json_encode([
                'rowId' => (string) $row->row_id,
                'source' => (string) $row->source,
                'itemKey' => (string) $row->item_key,
                'category' => (string) ($row->category ?? ''),
                'name' => (string) ($row->name ?? ''),
                'brand' => (string) ($row->brand ?? ''),
                'model' => (string) ($row->model ?? ''),
            ]);

            $jsCode = 'Livewire.dispatch("open-inventory-detail-modal", ' . $payload . ')';

            return e($jsCode);
        };

        return [
            Column::make('Source', 'source')
                ->sortable()
                ->searchable()
                ->format(fn ($v) => match ($v) {
                    'it_leasing' => 'IT Leasing',
                    'it_leasing_inclusion' => 'IT Leasing (Inclusion)',
                    'fixed_asset' => 'Fixed Asset',
                    default => $v,
                })
                ->html(),

            Column::make('Category', 'category')->sortable()->searchable(),

            Column::make('Name', 'name')
                ->sortable()->searchable()
                ->format(fn ($v, $row) => '<span onclick="' . $click($row) . '" class="cursor-pointer block font-medium text-blue-600 dark:text-blue-400 hover:underline">' . e($v ?? '—') . '</span>')
                ->html(),

            Column::make('Brand', 'brand')->sortable()->searchable(),
            Column::make('Model', 'model')->sortable()->searchable(),

            Column::make('Purchase Date', 'purchase_date')
                ->sortable()
                ->format(fn ($v) => $v ? \Carbon\Carbon::parse($v)->format('M d, Y') : '—'),

            Column::make('Unit Price', 'unit_price')
                ->sortable()
                ->format(fn ($v) => number_format((float) $v, 2)),

            Column::make('Quantity in stock', 'quantity_in_stock')->sortable(),

            Column::make('Inventory value', 'inventory_value')
                ->sortable()
                ->format(fn ($v) => number_format((float) $v, 2)),
        ];
    }
}
