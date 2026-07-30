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

        // Highlight inclusion rows so they're visually distinct from main items
        $this->setTrAttributes(function ($row) {
            return ($row->source === 'it_leasing_inclusion')
                ? ['class' => 'tr-inclusion-row']
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
