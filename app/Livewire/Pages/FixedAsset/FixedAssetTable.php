<?php

namespace App\Livewire\Pages\FixedAsset;

use App\Models\FixedAsset;
use Throwable;
use Illuminate\Database\QueryException;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class FixedAssetTable extends DataTableComponent
{
    public string $tableName = 'fixed-asset-table';
    protected $model = FixedAsset::class;

    protected $listeners = ['confirmDeleteFixedAsset' => 'deleteItem'];

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
            ->setEmptyMessage('No fixed assets found.')
            ->setTheme('tailwind')
            ->setAdditionalSelects(['fixed_assets.status']);
    }

    /**
     * FILTERS
     */
    public function filters(): array
    {
        return [
            SelectFilter::make('Category')
                ->options(
                    FixedAsset::pluck('category', 'category')
                        ->prepend('All', '')
                        ->toArray()
                )
                ->filter(fn($query, $value) => $value ? $query->where('category', $value) : null),

            SelectFilter::make('Status')
                ->options([
                    '' => 'All',
                    'available' => 'Available',
                    'issued' => 'Issued',
                    'repair' => 'Repair',
                    'disposed' => 'Disposed',
                    'lost' => 'Lost',
                ])
                ->filter(fn($query, $value) => $value ? $query->where('status', $value) : null),
        ];
    }

    /**
     * DELETE INDIVIDUAL
     */
    public function deleteItem(int $id): void
    {
        try {
            $item = FixedAsset::find($id);

            if (!$item) {
                $this->dispatch('toast', message: 'Asset not found.', type: 'error');
                return;
            }

            $name = $item->asset_name ?? 'Asset';
            $item->delete();

            $this->setPage(1);
            $this->dispatch('$refresh');

            $this->dispatch('toast', message: "{$name} deleted successfully!", type: 'success');
        } catch (QueryException $e) {
            logger()->error('DB error deleting asset', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Database error occurred while deleting.', type: 'error');
        } catch (Throwable $e) {
            logger()->error('Unexpected error deleting asset', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Unexpected error occurred.', type: 'error');
        }
    }

    /**
     * BULK DELETE
     */
    public function deleteSelected(): void
    {
        try {
            $selected = $this->getSelected();

            if (empty($selected)) {
                $this->dispatch('toast', message: 'No items selected.', type: 'warning');
                return;
            }

            FixedAsset::whereIn('id', $selected)->delete();

            $this->clearSelected();
            $this->setPage(1);
            $this->dispatch('$refresh');

            $this->dispatch(
                'toast',
                message: count($selected) . ' asset(s) deleted successfully!',
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
     * COLUMNS
     */
    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable()->searchable(),
            Column::make('Asset Tag', 'asset_tag')->sortable()->searchable(),
            Column::make('Category', 'category')->sortable()->searchable(),
            Column::make('Asset Name', 'asset_name')->sortable()->searchable(),
            Column::make('Serial Number', 'serial_number')->sortable()->searchable(),
            Column::make('Brand', 'brand')->sortable()->searchable(),
            Column::make('Model', 'model')->sortable()->searchable(),
            Column::make('Cost', 'purchase_cost')->sortable()->format(fn($value) => "₱ " . number_format($value, 2)),
            Column::make('Supplier', 'supplier')->sortable(),
            Column::make('Assigned To', 'assigned_employee')->sortable()->searchable(),
            Column::make('Location', 'location')->sortable()->searchable(),
            Column::make('Status', 'status')->sortable()->searchable()
                ->label(fn($row) => view('livewire.pages.fixed-asset.partials.status-badge', ['status' => $row->status])->render())->html(),
            Column::make('Created At', 'created_at')->sortable()->format(fn($value) => $value->format('M d, Y')),
            Column::make('Actions')->label(fn($row) => view('livewire.pages.fixed-asset.partials.actions', ['item' => $row])->render())->html(),
        ];
    }
}
