<?php

namespace App\Livewire\Pages\FixedAsset;

use Livewire\Component;
use App\Models\FixedAsset;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Throwable;

class FixedAssetEdit extends Component
{
    public $assetId;

    public $asset_tag;
    public $asset_name;
    public $category;
    public $serial_number;
    public $brand;
    public $model;
    public $cost;
    public $supplier;
    public $assigned_to;
    public $class;
    public $location;
    public $status;
    public $condition;
    public $purchase_date;
    public $purchase_order_no;
    public $warranty_expiration;
    public $remarks;

    public function mount(FixedAsset $asset)
    {
        $this->assetId = $asset->id;

        $this->asset_tag = $asset->asset_tag;
        $this->asset_name = $asset->asset_name;
        $this->category = $asset->category;
        $this->serial_number = $asset->serial_number;
        $this->brand = $asset->brand;
        $this->model = $asset->model;
        $this->cost = $asset->cost;
        $this->supplier = $asset->supplier;
        $this->assigned_to = $asset->assigned_to;
        $this->class = $asset->class;
        $this->location = $asset->location;
        $this->status = $asset->status;
        $this->condition = $asset->condition;
        // Format dates as YYYY-MM-DD for HTML date inputs
        $this->purchase_date = $asset->purchase_date?->format('Y-m-d');
        $this->warranty_expiration = $asset->warranty_expiration?->format('Y-m-d');

        $this->purchase_order_no = $asset->purchase_order_no;
        $this->remarks = $asset->remarks;
    }

    public function update()
    {
        try {
            $validated = $this->validate([
                'asset_tag' => 'nullable|string|max:255',
                'asset_name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'serial_number' => 'nullable|string|max:255',
                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'cost' => 'nullable|numeric',
                'supplier' => 'nullable|string|max:255',
                'assigned_to' => 'nullable|string|max:255',
                'class' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255',
                'status' => 'nullable|string|max:100',
                'condition' => 'nullable|string|max:100',
                'purchase_date' => 'nullable|date',
                'purchase_order_no' => 'nullable|string|max:255',
                'warranty_expiration' => 'nullable|date',
                'remarks' => 'nullable|string',
            ]);

            FixedAsset::findOrFail($this->assetId)->update($validated);

            session()->flash('toast', [
                'message' => 'Fixed asset updated successfully!',
                'type' => 'success',
            ]);

            return $this->redirect(route('fixed-asset.index'), navigate: true);

        } catch (ValidationException $e) {
            $this->dispatch('toast', message: 'Please check required fields.', type: 'error');
            throw $e;

        } catch (QueryException $e) {
            $this->dispatch('toast', message: 'Database error occurred.', type: 'error');
            logger()->error('DB Error in FixedAssetEdit', ['error' => $e->getMessage()]);

        } catch (Throwable $e) {
            $this->dispatch('toast', message: 'Unexpected error occurred.', type: 'error');
            logger()->error('Error in FixedAssetEdit', ['error' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.pages.fixed-asset.fixed-asset-edit');
    }
}
