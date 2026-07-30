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

        $this->setTrAttributes(function ($row) {
            return ($row->source === 'it_leasing_inclusion')
                ? ['class' => 'tr-inclusion-row']
                : [];
        });
    }

    public function builder(): Builder
    {
        $query = InventoryReportRow::queryUnion();

        // --- Date range filter (purchase_date) ---
        $dateFilter = $this->getAppliedFilterWithValue('purchase_date');
         \Log::info('DateFilter raw value:', ['value' => $dateFilter]);
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

        // --- Model filter ---
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
            Column::make('Name', 'name')->sortable()->searchable(),
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
