<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\InventoryReorderSetting;

class ReorderSettingModal extends Component
{
    public string $source = '';
    public string $item_key = '';

    public ?string $category = null;
    public ?string $name = null;
    public ?string $brand = null;
    public ?string $model = null;

    public int $reorder_level = 0;
    public ?int $reorder_time_days = null;
    public bool $discontinued = false;

    #[On('openReorderModal')]
    public function openModal($payload = null): void
    {
        logger('REORDER MODAL FIRED', ['payload' => $payload]);
        if (!is_array($payload)) return;

        // set identity
        $this->source   = $payload['source'] ?? '';
        $this->item_key = $payload['item_key'] ?? null;

        // display info
        $this->category = $payload['category'] ?? null;
        $this->name     = $payload['name'] ?? null;
        $this->brand    = $payload['brand'] ?? null;
        $this->model    = $payload['model'] ?? null;

        // load existing settings (if any)
        $setting = InventoryReorderSetting::query()
            ->where('source', $this->source)
            ->where('item_key', $this->item_key)
            ->first();

        $this->reorder_level     = (int) ($setting->reorder_level ?? ($payload['reorder_level'] ?? 0));
        $this->reorder_time_days = $setting?->reorder_time_days ?? ($payload['reorder_time_days'] ?? null);
        $this->discontinued      = (bool) ($setting->discontinued ?? ($payload['discontinued'] ?? false));
    }

    public function save(): void
    {
        $this->validate([
            'source' => ['required', 'string'],
            'item_key' => ['required', 'string'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'reorder_time_days' => ['nullable', 'integer', 'min:0'],
            'discontinued' => ['boolean'],
        ]);

        InventoryReorderSetting::updateOrCreate(
            [
                'source' => $this->source,
                'item_key' => $this->item_key,
            ],
            [
                'category' => $this->category,
                'name' => $this->name,
                'brand' => $this->brand,
                'model' => $this->model,
                'reorder_level' => $this->reorder_level,
                'reorder_time_days' => $this->reorder_time_days,
                'discontinued' => $this->discontinued,
            ]
        );

        $this->dispatch('flux-modal:close', name: 'reorder-setting');
        $this->dispatch('refreshInventoryTable');
    }

    public function render()
    {
        return view('livewire.modals.reorder-setting-modal');
    }
}
