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
    public $brand;
    public $model;
    public $purchase_cost;
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

    public function mount(ItLeasing $item)
    {
        $this->item = $item;

        $this->category = $item->category;
        $this->item_name = $item->item_name;
        $this->serial_number = $item->serial_number;
        $this->brand = $item->brand;
        $this->model = $item->model;
        $this->purchase_cost = $item->purchase_cost;
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
    }

    public function update()
    {
        try {
            $validated = $this->validate([
                'category' => 'required|string|max:255',
                'item_name' => 'required|string|max:255',
                'serial_number' => "required|string|unique:it_leasings,serial_number,{$this->item->id}",
                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'purchase_cost' => 'nullable|numeric',
                'supplier' => 'nullable|string|max:255',
                'purchase_order_no' => 'nullable|string|max:255',
                'purchase_date' => 'nullable|date',
                'warranty_expiration' => 'nullable|date',
                'assigned_company' => 'required|string|max:255',
                'assigned_employee' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255',
                'status' => 'required|in:available,deployed,in_repair,returned,lost',
                'condition' => 'nullable|in:new,good,fair,poor',
                'remarks' => 'nullable|string',
            ]);

            $this->item->update($validated);

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
