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
    public array $inclusions = [];

    /**
     * Stores the page number the user came from.
     * Captured from the ?page= query param passed by actions.blade.php.
     * Stored as a Livewire property so it survives the full component lifecycle.
     */
    public int $returnPage = 1;

    public function mount(ItLeasing $item)
    {
        $this->item = $item;

        // actions.blade.php passes ?page=N (reads from ?it-leasing-table-page=N in the URL)
        $this->returnPage = (int) request()->get('page', 1);

        $this->category              = $item->category;
        $this->item_name             = $item->item_name;
        $this->serial_number         = $item->serial_number;
        $this->charger_serial_number = $item->charger_serial_number;
        $this->brand                 = $item->brand;
        $this->model                 = $item->model;
        $this->purchase_cost         = $item->purchase_cost;
        $this->rental_rate_per_month = $item->rental_rate_per_month;
        $this->supplier              = $item->supplier;
        $this->purchase_order_no     = $item->purchase_order_no;
        $this->purchase_date         = $item->purchase_date?->format('Y-m-d');
        $this->warranty_expiration   = $item->warranty_expiration?->format('Y-m-d');
        $this->assigned_company      = $item->assigned_company;
        $this->assigned_employee     = $item->assigned_employee;
        $this->location              = $item->location;
        $this->status                = $item->status ?: 'available';
        $this->condition             = $item->condition ?: 'new';
        $this->remarks               = $item->remarks;
        $this->inclusions            = is_array($item->inclusions) ? $item->inclusions : [];
    }

    protected function validationRules(): array
    {
        return [
            'category'               => 'required|string|max:255',
            'item_name'              => 'required|string|max:255',
            'serial_number'          => "required|string|max:255|unique:it_leasings,serial_number,{$this->item->id}",
            'charger_serial_number'  => "nullable|string|max:255|unique:it_leasings,charger_serial_number,{$this->item->id}",
            'brand'                  => 'nullable|string|max:255',
            'model'                  => 'nullable|string|max:255',
            'purchase_cost'          => 'nullable|numeric',
            'rental_rate_per_month'  => 'nullable|numeric',
            'supplier'               => 'nullable|string|max:255',
            'purchase_order_no'      => 'nullable|string|max:255',
            'purchase_date'          => 'nullable|date',
            'warranty_expiration'    => 'nullable|date',
            'assigned_company'       => 'nullable|string|max:255',
            'assigned_employee'      => 'nullable|string|max:255',
            'location'               => 'nullable|string|max:255',
            'status'                 => 'nullable|in:available,deployed,in_repair,returned,lost',
            'condition'              => 'nullable|in:new,good,fair,poor',
            'remarks'                => 'nullable|string',
            'inclusions'             => 'nullable|array',
            'inclusions.*'           => 'nullable|string|max:255',
        ];
    }

    public function updatedBrand($value)
    {
        if (!empty($this->rental_rate_per_month)) return;

        match (strtoupper(trim((string) $value))) {
            'HP'     => $this->rental_rate_per_month = 3000.00,
            'LENOVO' => $this->rental_rate_per_month = 3500.00,
            default  => null,
        };
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
            $validated = $this->validate($this->validationRules());

            $validated['inclusions'] = $this->inclusions ?? [];

            $this->item->update($validated);

            Cache::forget('it_leasing_categories');
            Cache::forget('it_leasing_serial_numbers');

            session()->flash('toast', [
                'message' => 'IT Leasing item updated successfully!',
                'type'    => 'success',
            ]);

            // Redirect back to the exact page the user came from.
            // Rappasoft reads pagination from ?it-leasing-table-page=N in the URL.
            $redirectUrl = $this->returnPage > 1
                ? route('it-leasing.index') . '?it-leasing-tablePage=' . $this->returnPage
                : route('it-leasing.index');

            return $this->redirect($redirectUrl, navigate: true);

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
