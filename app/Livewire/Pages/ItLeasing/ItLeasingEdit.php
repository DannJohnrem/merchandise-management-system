<?php

namespace App\Livewire\Pages\ItLeasing;

use Livewire\Component;
use App\Models\ItLeasing;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Throwable;

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
    public $inclusions = [];

    public function mount(ItLeasing $item)
    {
        $this->item = $item;

        foreach ($item->toArray() as $key => $value) {
            $this->$key = $value;
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

        match (strtoupper(trim($value))) {
            'HP' => $this->rental_rate_per_month = 3000.00,
            'LENOVO' => $this->rental_rate_per_month = 3500.00,
            default => null,
        };
    }

    public function update()
    {
        try {
            $this->item->update([
                'category' => $this->category,
                'item_name' => $this->item_name,
                'serial_number' => $this->serial_number,
                'charger_serial_number' => $this->charger_serial_number,
                'brand' => $this->brand,
                'model' => $this->model,
                'purchase_cost' => $this->purchase_cost,
                'rental_rate_per_month' => $this->rental_rate_per_month,
                'supplier' => $this->supplier,
                'purchase_order_no' => $this->purchase_order_no,
                'purchase_date' => $this->purchase_date,
                'warranty_expiration' => $this->warranty_expiration,
                'assigned_company' => $this->assigned_company,
                'assigned_employee' => $this->assigned_employee,
                'location' => $this->location,
                'status' => $this->status,
                'condition' => $this->condition,
                'remarks' => $this->remarks,
                'inclusions' => $this->inclusions,
            ]);

            session()->flash('toast', [
                'message' => 'IT Leasing item updated successfully!',
                'type' => 'success',
            ]);

            $this->redirect(route('it-leasing.index'), navigate: true);

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
