<?php

namespace App\Livewire\Pages\FixedAsset;

use Livewire\Component;
use App\Models\FixedAsset;
use App\Models\ClassModel;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Throwable;

class FixedAssetEdit extends Component
{
    public FixedAsset $asset;

    // Public properties for form binding
    public $asset_tag;
    public $category;
    public $asset_name;
    public $serial_number;
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

    public $classes = [];

    public function mount(FixedAsset $asset)
    {
        $this->asset = $asset;

        // Populate public properties with existing data
        $this->asset_tag = $asset->asset_tag;
        $this->category = $asset->category;
        $this->asset_name = $asset->asset_name;
        $this->serial_number = $asset->serial_number;
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

        // Load available classes
        $this->classes = ClassModel::orderBy('name')->get();
    }

    public function update()
    {
        try {
            $validated = $this->validate([
                'asset_tag' => 'nullable|string|max:255',
                'category' => 'required|string|max:255',
                'asset_name' => 'required|string|max:255',
                'serial_number' => "nullable|string|unique:fixed_assets,serial_number,{$this->asset->id}",
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
            ]);

            // Update the model using the public properties
            $this->asset->update([
                'asset_tag' => $this->asset_tag,
                'category' => $this->category,
                'asset_name' => $this->asset_name,
                'serial_number' => $this->serial_number,
                'brand' => $this->brand,
                'model' => $this->model,
                'purchase_cost' => $this->purchase_cost,
                'supplier' => $this->supplier,
                'assigned_employee' => $this->assigned_employee,
                'asset_class' => $this->asset_class,
                'location' => $this->location,
                'status' => $this->status,
                'condition' => $this->condition,
                'purchase_date' => $this->purchase_date,
                'purchase_order_no' => $this->purchase_order_no,
                'warranty_expiration' => $this->warranty_expiration,
                'remarks' => $this->remarks,
            ]);

            session()->flash('toast', [
                'message' => 'Fixed asset updated successfully!',
                'type' => 'success',
            ]);

            return redirect()->route('fixed-asset.index');

        } catch (ValidationException $e) {
            $this->dispatch('toast', message: 'Please check required fields.', type: 'error');
            throw $e;

        } catch (QueryException $e) {
            logger()->error('Database error updating Fixed Asset', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Database error occurred.', type: 'error');

        } catch (Throwable $e) {
            logger()->error('Unexpected error in FixedAssetEdit', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Unexpected error occurred.', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.pages.fixed-asset.fixed-asset-edit');
    }
}
