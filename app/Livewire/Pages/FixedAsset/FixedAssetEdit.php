<?php

namespace App\Livewire\Pages\FixedAsset;

use Livewire\Component;
use App\Models\FixedAsset;
use App\Models\ClassModel;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Throwable;
use Illuminate\Support\Facades\Cache;

class FixedAssetEdit extends Component
{
    public FixedAsset $asset;

    // Public properties for form binding
    public $asset_tag;
    public $category;
    public $asset_name;
    public $serial_number;
    public $charger_serial_number;
    public $brand;
    public $model;
    public $purchase_cost;
    public $supplier;
    public $assigned_employee;
    public $asset_class;
    public $location;
    public $status;
    public $condition;
    public $purchase_date;
    public $purchase_order_no;
    public $warranty_expiration;
    public $remarks;
    public $inclusions = [];

    public $classes = [];

    public function mount(FixedAsset $asset)
    {
        $this->asset = $asset;

        // Populate properties with existing data
        $this->asset_tag = $asset->asset_tag;
        $this->category = $asset->category;
        $this->asset_name = $asset->asset_name;
        $this->serial_number = $asset->serial_number;
        $this->charger_serial_number = $asset->charger_serial_number;
        $this->brand = $asset->brand;
        $this->model = $asset->model;
        $this->purchase_cost = $asset->purchase_cost;
        $this->supplier = $asset->supplier;
        $this->assigned_employee = $asset->assigned_employee;
        $this->asset_class = $asset->asset_class;
        $this->location = $asset->location;
        $this->status = $asset->status ?: 'available';
        $this->condition = $asset->condition ?: 'new';
        $this->purchase_date = $asset->purchase_date?->format('Y-m-d');
        $this->purchase_order_no = $asset->purchase_order_no;
        $this->warranty_expiration = $asset->warranty_expiration?->format('Y-m-d');
        $this->remarks = $asset->remarks;

        // Decode inclusions from JSON, fallback to empty array
        $this->inclusions = $asset->inclusions ? json_decode($asset->inclusions, true) : [];

        // Load available classes
        $this->classes =  Cache::remember('fixed_asset_classes', 3600, function () {
            return ClassModel::orderBy('name')->get();
        });
    }

    public function addInclusion()
    {
        $this->inclusions[] = '';
    }

    public function removeInclusion($index)
    {
        unset($this->inclusions[$index]);
        $this->inclusions = array_values($this->inclusions);
    }

    public function update()
    {
        try {
            $validated = $this->validate([
                'asset_tag' => 'nullable|string|max:255',
                'category' => 'required|string|max:255',
                'asset_name' => 'required|string|max:255',
                'serial_number' => "nullable|string|unique:fixed_assets,serial_number,{$this->asset->id}",
                'charger_serial_number' => "nullable|string|unique:fixed_assets,charger_serial_number,{$this->asset->id}",
                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'purchase_cost' => 'nullable|numeric',
                'supplier' => 'nullable|string|max:255',
                'assigned_employee' => 'nullable|string|max:255',
                'asset_class' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255',
                'status' => 'nullable|in:available,issued,repair,disposed,lost',
                'condition' => 'nullable|in:new,good,fair,poor',
                'purchase_date' => 'nullable|date',
                'purchase_order_no' => 'nullable|string|max:255',
                'warranty_expiration' => 'nullable|date',
                'remarks' => 'nullable|string',
                'inclusions' => 'nullable|array',
                'inclusions.*' => 'nullable|string|max:255',
            ]);

            // Store inclusions as JSON
            $validated['inclusions'] = json_encode($this->inclusions);

            $this->asset->update($validated);

            Cache::forget('fixed_asset_categories');

            session()->flash('toast', [
                'message' => 'Fixed asset updated successfully!',
                'type' => 'success',
            ]);

            return $this->redirect(route('fixed-asset.index'), navigate: true);

        } catch (ValidationException $e) {
            $this->dispatch('toast', message: 'Please check required fields.', type: 'error');
            throw $e;

        } catch (QueryException $e) {
            logger()->error('Database error updating Fixed Asset', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Database error occurred.', type: 'error');

        } catch (Throwable $e) {
            logger()->error('Unexpected error updating Fixed Asset', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Unexpected error occurred.', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.pages.fixed-asset.fixed-asset-edit');
    }
}
