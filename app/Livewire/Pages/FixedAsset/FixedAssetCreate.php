<?php

namespace App\Livewire\Pages\FixedAsset;

use App\Models\ClassModel;
use Livewire\Component;
use App\Models\FixedAsset;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Throwable;

class FixedAssetCreate extends Component
{
    public array $items = [];
    public $classes = [];

    public function mount()
    {
        $this->items[] = $this->blankItem();

        // lOAD CLASSES
        $this->classes = ClassModel::orderBy('name')->get();
    }

    protected function blankItem(): array
    {
        return [
            'asset_tag' => null,
            'category' => null,
            'asset_name' => null,
            'serial_number' => null,
            'brand' => null,
            'model' => null,
            'cost' => null,
            'supplier' => null,
            'assigned_to' => null,
            'class' => null,
            'location' => null,
            'status' => null,
            'condition' => null,
            'purchase_date' => null,
            'purchase_order_no' => null,
            'warranty_expiration' => null,
            'remarks' => null,
        ];
    }

    public function addItem()
    {
        $this->items[] = $this->blankItem();
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // reindex
    }

    public function save()
    {
        try {
            $this->validate([
                'items' => 'required|array|min:1',

                'items.*.asset_name' => 'required|string|max:255',
                'items.*.category' => 'required|string|max:255',

                'items.*.asset_tag' => 'nullable|string|max:255',
                'items.*.serial_number' => 'nullable|string|max:255',
                'items.*.brand' => 'nullable|string|max:255',
                'items.*.model' => 'nullable|string|max:255',
                'items.*.cost' => 'nullable|numeric',
                'items.*.supplier' => 'nullable|string|max:255',
                'items.*.assigned_to' => 'nullable|string|max:255',
                'items.*.class' => 'nullable|string|max:255',
                'items.*.location' => 'nullable|string|max:255',
                'items.*.status' => 'nullable|string|max:100',
                'items.*.condition' => 'nullable|string|max:100',
                'items.*.purchase_date' => 'nullable|date',
                'items.*.purchase_order_no' => 'nullable|string|max:255',
                'items.*.warranty_expiration' => 'nullable|date',
                'items.*.remarks' => 'nullable|string',
            ]);

            foreach ($this->items as $item) {
                FixedAsset::create($item);
            }

            session()->flash('toast', [
                'message' => 'Fixed assets created successfully!',
                'type' => 'success',
            ]);

            return $this->redirect(route('fixed-asset.index'), navigate: true);

        } catch (ValidationException $e) {
            $this->dispatch('toast', message: 'Please fix validation errors.', type: 'error');
            throw $e;

        } catch (QueryException $e) {
            logger()->error('DB Error', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Database error occurred.', type: 'error');

        } catch (Throwable $e) {
            logger()->error('Unexpected Error', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Unexpected error occurred.', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.pages.fixed-asset.fixed-asset-create');
    }
}
