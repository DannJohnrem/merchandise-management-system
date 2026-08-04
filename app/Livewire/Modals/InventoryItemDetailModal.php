<?php

namespace App\Livewire\Modals;

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
        $this->source = $source;
        $this->itemKey = $itemKey;
        $this->category = $category;
        $this->name = $name;
        $this->brand = $brand;
        $this->model = $model;
    }

    public function render()
    {
        return view('livewire.modals.inventory-item-detail-modal');
    }
}
