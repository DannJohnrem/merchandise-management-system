<?php

namespace App\Livewire\Pages\ItLeasing;

use Throwable;
use Livewire\Component;
use App\Models\ItLeasing;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class ItLeasingEdit extends Component
{
    public ItLeasing $item;

    public $category;
    public $item_name;
    public $serial_number;
    public $charger_serial_number;
    public $brand;
    public $model;
    public $purchase_cost;
    public $rental_rate_per_month;
    public $supplier;
    public $purchase_order_no;
    public $purchase_date;
    public $warranty_expiration;
    public $assigned_company;
    public $assigned_employee;
    public $location;
    public $status;
    public $condition;
    public $remarks;

    // ✅ must be array for blade foreach
    public array $inclusions = [];

    public function mount(ItLeasing $item)
    {
        $this->item = $item;

        // ✅ Manual assign (same style as FixedAssetEdit) to avoid JSON string issues
        $this->category = $item->category;
        $this->item_name = $item->item_name;
        $this->serial_number = $item->serial_number;
        $this->charger_serial_number = $item->charger_serial_number;
        $this->brand = $item->brand;
        $this->model = $item->model;
        $this->purchase_cost = $item->purchase_cost;
        $this->rental_rate_per_month = $item->rental_rate_per_month;
        $this->supplier = $item->supplier;
        $this->purchase_order_no = $item->purchase_order_no;
        $this->purchase_date = $item->purchase_date?->format('Y-m-d');
        $this->warranty_expiration = $item->warranty_expiration?->format('Y-m-d');
        $this->assigned_company = $item->assigned_company;
        $this->assigned_employee = $item->assigned_employee;
        $this->location = $item->location;
        $this->status = $item->status ?: 'available';
        $this->condition = $item->condition ?: 'new';
        $this->remarks = $item->remarks;

        // ✅ Decode inclusions JSON -> array (safe fallback)
        $this->inclusions = $item->inclusions ? json_decode($item->inclusions, true) : [];
        if (!is_array($this->inclusions)) {
            $this->inclusions = [];
        }
    }

    /**
     * 🔥 AUTO-FILL RENTAL RATE WHEN BRAND CHANGES
     */
    public function updatedBrand($value)
    {
        // Do not override manual input
        if (!empty($this->rental_rate_per_month)) {
            return;
        }

        match (strtoupper(trim((string) $value))) {
            'HP' => $this->rental_rate_per_month = 3000.00,
            'LENOVO' => $this->rental_rate_per_month = 3500.00,
            default => null,
        };
    }

    // ✅ Same helpers as FixedAssetEdit (optional but useful)
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
                'category' => 'required|string|max:255',
                'item_name' => 'required|string|max:255',

                'serial_number' => "nullable|string|max:255|unique:it_leasings,serial_number,{$this->item->id}",
                'charger_serial_number' => "nullable|string|max:255|unique:it_leasings,charger_serial_number,{$this->item->id}",

                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',

                'purchase_cost' => 'nullable|numeric',
                'rental_rate_per_month' => 'nullable|numeric',

                'supplier' => 'nullable|string|max:255',
                'purchase_order_no' => 'nullable|string|max:255',

                'purchase_date' => 'nullable|date',
                'warranty_expiration' => 'nullable|date',

                'assigned_company' => 'nullable|string|max:255',
                'assigned_employee' => 'nullable|string|max:255',

                'location' => 'nullable|string|max:255',

                'status' => 'nullable|in:available,deployed,in_repair,returned,lost',
                'condition' => 'nullable|in:new,good,fair,poor',

                'remarks' => 'nullable|string',

                'inclusions' => 'nullable|array',
                'inclusions.*' => 'nullable|string|max:255',
            ]);

            // ✅ Store inclusions as JSON (same approach as FixedAssetEdit)
            $validated['inclusions'] = json_encode($this->inclusions ?? []);

            $this->item->update($validated);

            // ✅ clear cached filters (like your table filters)
            Cache::forget('it_leasing_categories');
            Cache::forget('it_leasing_serial_numbers');

            session()->flash('toast', [
                'message' => 'IT Leasing item updated successfully!',
                'type' => 'success',
            ]);

            return $this->redirect(route('it-leasing.index'), navigate: true);

        } catch (ValidationException $e) {
            $this->dispatch('toast', message: 'Please check required fields.', type: 'error');
            throw $e;

        } catch (QueryException $e) {
            logger()->error('Database error updating IT Leasing', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Database error occurred.', type: 'error');

        } catch (Throwable $e) {
            logger()->error('Unexpected error in ItLeasingEdit', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Unexpected error occurred.', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.pages.it-leasing.it-leasing-edit');
    }
}
