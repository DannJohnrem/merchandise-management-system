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
     * Filters
     */
    public function filters(): array
    {
        return [
            SelectFilter::make('Category')
                ->options(
                    ItLeasing::pluck('category', 'category')
                        ->prepend('All', '')
                        ->toArray()
                )
                ->filter(
                    fn($query, $value) =>
                    $value ? $query->where('category', $value) : null
                ),

            SelectFilter::make('Status')
                ->options([
                    '' => 'All',
                    'available'  => 'Available',
                    'deployed'   => 'Deployed',
                    'in_repair'  => 'In Repair',
                    'returned'   => 'Returned',
                    'lost'       => 'Lost',
                ])
                ->filter(fn($query, $value) => $value ? $query->where('status', $value) : null),
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
        } catch (QueryException $e) {
            logger()->error('DB error deleting item', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Database error occurred while deleting item.', type: 'error');
        } catch (Throwable $e) {
            logger()->error('Unexpected error deleting item', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Unexpected error occurred while deleting item.', type: 'error');
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

            $this->dispatch(
                'toast',
                message: count($selected) . ' item(s) deleted successfully.',
                type: 'success'
            );
        } catch (QueryException $e) {
            logger()->error('DB bulk delete error', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Database error occurred.', type: 'error');
        } catch (Throwable $e) {
            logger()->error('Unexpected bulk delete error', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Unexpected error occurred.', type: 'error');
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
                ->format(fn($value) => '₱ ' . number_format($value, 2)),
            Column::make('Assigned Company', 'assigned_company')->sortable()->searchable(),
            Column::make('Assigned Employee', 'assigned_employee')->sortable()->searchable()->format(fn($value) => $value ?: '—'),
            Column::make('Status', 'status')->sortable()->searchable()
                ->label(fn($row) => view('livewire.pages.it-leasing.partials.status-badge', ['status' => $row->status])->render())->html(),
            Column::make('Created At', 'created_at')
                ->sortable()
                ->format(fn($value) => $value->format('M d, Y')),
            Column::make('Actions')
                ->label(
                    fn($row) =>
                    view('livewire.pages.it-leasing.partials.actions', [
                        'item' => $row,
                    ])->render()
                )
                ->html(),
        ];
    }
}
