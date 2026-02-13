<?php

namespace App\Livewire\Tables;

use App\Models\InventoryReportRow;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class InventoryListTable extends DataTableComponent
{
    public function configure(): void
    {
        $this->setPrimaryKey('row_id');
        $this->setPerPage(25);
        $this->setPerPageAccepted([10, 25, 50, 100]);
    }

    public function builder(): Builder
    {
        return InventoryReportRow::queryUnion();
    }

    public function columns(): array
    {
        return [
            Column::make('Source', 'source')->sortable()->searchable(),
            Column::make('Asset Tag', 'asset_tag')->sortable()->searchable(),
            Column::make('Category', 'category')->sortable()->searchable(),
            Column::make('Item Name', 'item_name')->sortable()->searchable(),
            Column::make('Serial', 'serial_number')->sortable()->searchable(),
            Column::make('Brand', 'brand')->sortable()->searchable(),
            Column::make('Model', 'model')->sortable()->searchable(),

            Column::make('Cost', 'purchase_cost')
                ->sortable()
                ->format(fn ($value) => is_null($value) ? '' : number_format((float) $value, 2)),

            Column::make('Assigned Employee', 'assigned_employee')->sortable()->searchable(),
            Column::make('Location', 'location')->sortable()->searchable(),
            Column::make('Status', 'status')->sortable()->searchable(),
            Column::make('Condition', 'item_condition')->sortable()->searchable(),
            Column::make('Purchase Date', 'purchase_date')->sortable(),
            Column::make('Warranty Exp.', 'warranty_expiration')->sortable(),
        ];
    }
}
