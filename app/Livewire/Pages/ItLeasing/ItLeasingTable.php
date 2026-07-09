<?php

namespace App\Livewire\Pages\ItLeasing;

use Throwable;
use App\Models\ItLeasing;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class ItLeasingTable extends DataTableComponent
{
    public string $tableName = 'it-leasing-table';
    public bool $isItLeasingTable = true;
    protected $model = ItLeasing::class;

    protected $listeners = [
        'confirmDeleteItLeasing' => 'deleteItem',
    ];

    public array $bulkActions = [
        'deleteSelected' => 'Delete Selected',
        'generateDeliveryReceipt' => 'Generate DR',
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setPerPage(10)
            ->setPerPageAccepted([10, 25, 50, 100])
            ->setDefaultSort('created_at', 'desc')
            ->setColumnSelectDisabled(false)
            ->setSearchDebounce(300)
            ->setBulkActionsEnabled()
            ->setEmptyMessage('No IT leasing items found.')
            ->setTheme('tailwind');
    }

    /**
     * Limit selected columns for performance.
     */
    public function builder(): Builder
    {
        return ItLeasing::query()->select([
            'id',
            'category',
            'item_name',
            'serial_number',
            'brand',
            'model',
            'purchase_cost',
            'assigned_company',
            'assigned_employee',
            'status',
            'created_at',
        ]);
    }

    /**
     * Define available filters for the data table.
     */
    public function filters(): array
    {
        return [
            SelectFilter::make('Category')
                ->options(
                    Cache::remember('it_leasing_categories', 600, function () {
                        return ItLeasing::query()
                            ->select('category')
                            ->whereNotNull('category')
                            ->distinct()
                            ->orderBy('category')
                            ->pluck('category', 'category')
                            ->prepend('All', '')
                            ->toArray();
                    })
                )
                ->filter(fn ($query, $value) => $value ? $query->where('category', $value) : null),

            SelectFilter::make('Serial Number')
                ->options(
                    Cache::remember('it_leasing_serial_numbers', 600, function () {
                        return ItLeasing::query()
                            ->select('serial_number')
                            ->whereNotNull('serial_number')
                            ->distinct()
                            ->orderBy('serial_number')
                            ->pluck('serial_number', 'serial_number')
                            ->prepend('All', '')
                            ->toArray();
                    })
                )
                ->filter(fn ($query, $value) => $value ? $query->where('serial_number', $value) : null),

            SelectFilter::make('Model')
                ->options(
                    Cache::remember('it_leasing_models', 600, function () {
                        return ItLeasing::query()
                            ->select('model')
                            ->whereNotNull('model')
                            ->distinct()
                            ->orderBy('model')
                            ->pluck('model', 'model')
                            ->prepend('All', '')
                            ->toArray();
                    })
                )
                ->filter(fn ($query, $value) => $value ? $query->where('model', $value) : null),

            SelectFilter::make('Status')
                ->options([
                    ''          => 'All',
                    'available' => 'Available',
                    'deployed'  => 'Deployed',
                    'in_repair' => 'In Repair',
                    'returned'  => 'Returned',
                    'lost'      => 'Lost',
                ])
                ->filter(fn ($query, $value) => $value ? $query->where('status', $value) : null),
        ];
    }

    /**
     * Individual Delete
     */
    public function deleteItem(int $id): void
    {
        try {
            $item = ItLeasing::find($id);

            if (!$item) {
                $this->dispatch('toast', message: 'Item not found.', type: 'error');
                return;
            }

            $name = $item->serial_number ?? 'Item';
            $item->delete();

            Cache::forget('it_leasing_categories');
            Cache::forget('it_leasing_serial_numbers');

            $this->dispatch('toast', message: "{$name} deleted successfully!", type: 'success');
            $this->emitTotals();

        } catch (QueryException|Throwable $e) {
            logger()->error('Error deleting item', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Error occurred while deleting item.', type: 'error');
        }
    }

    /**
     * Bulk Delete
     */
    public function deleteSelected(): void
    {
        try {
            $selected = $this->getSelected();

            if (empty($selected)) {
                $this->dispatch('toast', message: 'No items selected.', type: 'warning');
                return;
            }

            ItLeasing::whereIn('id', $selected)->delete();

            Cache::forget('it_leasing_categories');
            Cache::forget('it_leasing_serial_numbers');

            $this->clearSelected();

            $this->dispatch('toast', message: count($selected).' item(s) deleted successfully.', type: 'success');
            $this->emitTotals();

        } catch (QueryException|Throwable $e) {
            logger()->error('Error bulk deleting items', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Error occurred during bulk delete.', type: 'error');
        }
    }

    public function generateDeliveryReceipt(): void
    {
        $selected = $this->getSelected();

        if (empty($selected)) {
            $this->dispatch('toast', message: 'No items selected.', type: 'warning');
            return;
        }

        $this->dispatch('openDrModal', ids: array_values($selected));
    }

    /**
     * Columns
     */
    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable()->searchable(),
            Column::make('Category', 'category')->sortable()->searchable(),
            Column::make('Item Name', 'item_name')->sortable()->searchable(),
            Column::make('Serial Number', 'serial_number')->sortable()->searchable(),
            Column::make('Brand', 'brand')->sortable()->searchable(),
            Column::make('Model', 'model')->sortable()->searchable(),
            Column::make('Purchase Cost', 'purchase_cost')
                ->sortable()
                ->format(fn ($value) => '₱ '.number_format($value, 2)),
            Column::make('Assigned Company', 'assigned_company')->sortable()->searchable(),
            Column::make('Assigned Employee', 'assigned_employee')
                ->sortable()
                ->searchable()
                ->format(fn ($value) => $value ?: '—'),
            Column::make('Status', 'status')
                ->sortable()
                ->searchable()
                ->label(fn ($row) =>
                    view('livewire.pages.it-leasing.partials.status-badge', ['status' => $row->status])->render()
                )
                ->html(),
            Column::make('Created At', 'created_at')
                ->sortable()
                ->format(fn ($value) => $value->format('M d, Y')),
            Column::make('Actions')
                ->label(fn ($row) =>
                    view('livewire.pages.it-leasing.partials.actions', ['item' => $row])->render()
                )
                ->html(),
        ];
    }

    /**
     * Emit page total and grand total (page 1 only) to parent.
     *
     * Called on every render so it catches all triggers:
     * page change, filter change, per-page change, delete, etc.
     */
    protected function emitTotals(): void
    {
        $pageRows  = $this->getRows();
        $pageTotal = $pageRows->sum('purchase_cost');

        // Grand total is expensive — only compute on page 1
        $grandTotal = 0;
        if ($this->getPage() === 1) {
            $grandTotal = $this
                ->applyFilters(ItLeasing::query())
                ->sum('purchase_cost');
        }

        $this->dispatch('totalsUpdated', [
            'pageTotal'   => (float) $pageTotal,
            'grandTotal'  => (float) $grandTotal,
            'currentPage' => $this->getPage(),
        ]);
    }

    /**
     * Called after every render.
     * This is the single source of truth for emitting totals —
     * covers initial load, pagination, filter changes, and deletes.
     */
    public function rendered(): void
    {
        $this->emitTotals();
    }
}
