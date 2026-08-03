<?php

namespace App\Livewire\Modals;

use App\Support\InventoryCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class InventoryItemDetailModal extends Component
{
    public string $source = '';
    public string $itemKey = '';
    public string $category = '';
    public string $name = '';
    public string $brand = '';
    public string $model = '';

    public array $availableUnits = [];
    public array $deployedUnits = [];

    protected const NOT_ON_HAND_STATUSES = ['deployed', 'lost'];

    #[On('open-inventory-detail-modal')]
    public function open(
        string $rowId,
        string $source,
        string $itemKey,
        string $category,
        string $name,
        string $brand,
        string $model
    ): void {
        // I-set agad ang header info galing sa row na kina-click, walang query
        $this->source = $source;
        $this->itemKey = $itemKey;
        $this->category = $category;
        $this->name = $name;
        $this->brand = $brand;
        $this->model = $model;

        // I-clear muna ang dating laman ng listahan para hindi makita
        // ang units ng previous item habang nagpe-fetch pa ng bago
        $this->availableUnits = [];
        $this->deployedUnits = [];

        $this->loadUnits();
    }

    protected function normalizeExpr(string $col): string
    {
        return "REGEXP_REPLACE(TRIM(COALESCE({$col}, '')), '\\\\s+', ' ')";
    }

    protected function itemKeyExpr(string $table, string $nameCol): string
    {
        $cat = $this->normalizeExpr("{$table}.category");
        $nm = $this->normalizeExpr("{$table}.{$nameCol}");
        $br = $this->normalizeExpr("{$table}.brand");
        $mo = $this->normalizeExpr("{$table}.model");

        return "LOWER(CONCAT_WS('|', {$cat}, {$nm}, {$br}, {$mo}))";
    }

    protected function loadUnits(): void
    {
        $cacheKey = InventoryCache::unitsKey($this->source, $this->itemKey);

        $cached = Cache::remember($cacheKey, now()->addHours(6), function () {
            $rows = match ($this->source) {
                'fixed_asset' => DB::table('fixed_assets')
                    ->whereNull('deleted_at')
                    ->whereRaw($this->itemKeyExpr('fixed_assets', 'asset_name') . ' = ?', [$this->itemKey])
                    ->select(
                        'id',
                        'asset_name as name',
                        'serial_number',
                        DB::raw('NULL as charger_serial_number'),
                        'status',
                        'purchase_date'
                    )
                    ->get(),

                'it_leasing' => DB::table('it_leasings')
                    ->whereNull('deleted_at')
                    ->whereRaw($this->itemKeyExpr('it_leasings', 'item_name') . ' = ?', [$this->itemKey])
                    ->select(
                        'id',
                        'item_name as name',
                        'serial_number',
                        'charger_serial_number',
                        'status',
                        'purchase_date'
                    )
                    ->get(),

                default => collect(),
            };

            return [
                'available' => $rows->whereNotIn('status', self::NOT_ON_HAND_STATUSES)
                    ->values()->map(fn ($r) => (array) $r)->toArray(),
                'deployed' => $rows->whereIn('status', self::NOT_ON_HAND_STATUSES)
                    ->values()->map(fn ($r) => (array) $r)->toArray(),
            ];
        });

        $this->availableUnits = $cached['available'];
        $this->deployedUnits = $cached['deployed'];
    }

    public function render()
    {
        return view('livewire.modals.inventory-item-detail-modal');
    }
}
