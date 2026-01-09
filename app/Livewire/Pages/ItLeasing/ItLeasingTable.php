<?php

namespace App\Livewire\Pages\ItLeasing;

use App\Models\ItLeasing;
use Throwable;
use Illuminate\Database\QueryException;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class ItLeasingTable extends DataTableComponent
{
    public string $tableName = 'it-leasing-table';
    public bool $isItLeasingTable = true;
    protected $model = ItLeasing::class;

    protected $listeners = [
        'confirmDeleteItem' => 'deleteItem',
    ];

    public array $bulkActions = [
        'deleteSelected' => '🗑️ Delete Selected',
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
            ->setTheme('tailwind')
            ->setAdditionalSelects(['it_leasings.status']);
    }

    /**
     * Recalculate totals when the current pagination page changes.
     *
     * Triggered by Livewire when the `page` property updates.
     *
     * @return void
     */
    public function updatedPage(): void
    {
        // dd('Page updated to: ' . $this->getPage());
        $this->emitTotals();
    }

    /**
     * Recalculate totals when table filters change.
     *
     * Called when Livewire updates the `filters` property.
     *
     * @return void
     */
    public function updatedFilters(): void
    {
        $this->emitTotals();
    }

    /**
     * Recalculate totals when the search term changes.
     *
     * @param string|array|null $value The new search value
     * @return void
     */
    public function updatedSearch(array|string|null $value): void
    {
        $this->emitTotals();
    }

    /**
     * Recalculate totals when the items-per-page setting changes.
     *
     * @param int|string $value The new per-page value
     * @return void
     */
    public function updatedPerPage(string|int $value): void
    {
        $this->emitTotals();
    }

    /**
     * Define available filters for the data table.
     *
     * Each returned Filter (eg. `SelectFilter`) is used by the
     * Livewire table to render filter UI and apply constraints to
     * the query. Filters should reference valid database columns
     * and return closures that modify the query when a value is
     * selected.
     *
     * @return array<string, \Rappasoft\LaravelLivewireTables\Views\Filters\Filter>
     */
    public function filters(): array
    {
        return [
            SelectFilter::make('Category')
                ->options(ItLeasing::pluck('category', 'category')->prepend('All', '')->toArray())
                ->filter(fn ($query, $value) => $value ? $query->where('category', $value) : null),

            SelectFilter::make('Serial Number')
                ->options(ItLeasing::pluck('serial_number', 'serial_number')->prepend('All', '')->toArray())
                ->filter(fn ($query, $value) => $value ? $query->where('serial_number', $value) : null),

            SelectFilter::make('Status')
                ->options([
                    '' => 'All',
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

            $this->setPage(1);
            $this->dispatch('$refresh');

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

            $this->clearSelected();
            $this->setPage(1);
            $this->dispatch('$refresh');
            $this->dispatch('toast', message: count($selected).' item(s) deleted successfully.', type: 'success');

            $this->emitTotals();
        } catch (QueryException|Throwable $e) {
            logger()->error('Error bulk deleting items', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Error occurred during bulk delete.', type: 'error');
        }
    }

    /**
     * Columns (placed at bottom for maintainability)
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
     * Emit the current page and grand totals for purchase_cost.
     *
     * @return void
     */
    protected function emitTotals(): void
    {
        $pageRows = $this->getRows();

        $pageTotal = $pageRows->sum('purchase_cost');

        // Only calculate grand total on page 1 for performance (it's only displayed there)
        $grandTotal = 0;
        if ($this->getPage() === 1) {
            $grandTotal = $this
                ->applyFilters(ItLeasing::query())
                ->sum('purchase_cost');
        }

        // Dispatch event to parent component (Livewire v3 syntax)
        $this->dispatch('totalsUpdated', [
            'pageTotal'  => (float) $pageTotal,
            'grandTotal' => (float) $grandTotal,
            'currentPage' => $this->getPage(),
        ]);
    }

    /**
     * Emit totals once after the component has rendered to provide
     * the parent component an initial page and grand total.
     *
     * This uses a static flag to ensure a single emission on first render.
     *
     * @return void
     */
    public function rendered(): void
    {
        static $emitted = false;

        if (! $emitted) {
            $this->emitTotals();
            $emitted = true;
        }
    }
}
