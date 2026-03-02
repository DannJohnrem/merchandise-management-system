<?php

namespace App\Livewire\Tables;

use App\Models\InventoryReportRow;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class InventoryListTable extends DataTableComponent
{
    protected $listeners = ['refreshInventoryTable' => '$refresh'];

    public function configure(): void
    {
        $this->setPrimaryKey('row_id');
        $this->setPerPage(25);
        $this->setPerPageAccepted([10, 25, 50, 100]);

        $this->setTrAttributes(function ($row) {
            return ((int) $row->for_reorder === 1)
                ? ['class' => 'tr-reorder-highlight']
                : [];
        });
    }

    public function builder(): Builder
    {
        return InventoryReportRow::queryUnion();
    }

    public function columns(): array
    {
        return [
            Column::make('For reorder', 'for_reorder')
                ->format(fn ($v) => (int) $v === 1 ? '<span title="For reorder">🚩</span>' : '')
                ->html(),

            Column::make('Source', 'source')->sortable()->searchable(),
            Column::make('DEBUG KEY', 'item_key'),
            Column::make('Category', 'category')->sortable()->searchable(),
            Column::make('Name', 'name')->sortable()->searchable(),
            Column::make('Brand', 'brand')->sortable()->searchable(),
            Column::make('Model', 'model')->sortable()->searchable(),

            Column::make('Unit Price', 'unit_price')
                ->sortable()
                ->format(fn ($v) => number_format((float) $v, 2)),

            Column::make('Quantity in stock', 'quantity_in_stock')->sortable(),

            Column::make('Inventory value', 'inventory_value')
                ->sortable()
                ->format(fn ($v) => number_format((float) $v, 2)),

            Column::make('Reorder level', 'reorder_level')->sortable(),
            Column::make('Reorder time (days)', 'reorder_time_days')->sortable(),

            Column::make('Discontinued?', 'discontinued')
                ->format(fn ($v) => (int) $v === 1 ? 'Yes' : 'No'),

            Column::make('Actions')
                ->label(fn ($row) => view('livewire.tables.inventory-list-table-actions', ['row' => $row])),
        ];
    }
}
