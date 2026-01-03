<?php

namespace App\Livewire\Pages\ItLeasing;

use Livewire\Component;
use App\Models\ItLeasing;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Throwable;

class ItLeasingCreate extends Component
{
    public array $items = [];

    /**
     * Initialize with one blank item
     */
    public function mount()
    {
        $this->items[] = $this->blankItem();
    }

    /**
     * Returns a blank item structure
     */
    protected function blankItem(): array
    {
        return [
            'category' => null,
            'item_name' => null,
            'serial_number' => null,
            'charger_serial_number' => null,
            'brand' => null,
            'model' => null,
            'purchase_cost' => null,
            'rental_rate_per_month' => null,
            'supplier' => null,
            'purchase_order_no' => null,
            'purchase_date' => null,
            'warranty_expiration' => null,
            'assigned_company' => null,
            'assigned_employee' => null,
            'location' => null,
            'status' => 'available',
            'condition' => 'new',
            'remarks' => null,
            'inclusions' => [],
        ];
    }

    /**
     * Add or remove items dynamically
     */
    public function addItem()
    {
        $this->items[] = $this->blankItem();
    }

    /**
     * Remove item at given index
     */
    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    /**
     * Add or remove inclusions for a specific item
     */
    public function addInclusion($itemIndex)
    {
        $this->items[$itemIndex]['inclusions'][] = '';
    }

    /**
     * Remove inclusion at given index for a specific item
     */
    public function removeInclusion($itemIndex, $inclusionIndex)
    {
        unset($this->items[$itemIndex]['inclusions'][$inclusionIndex]);
        $this->items[$itemIndex]['inclusions'] = array_values($this->items[$itemIndex]['inclusions']);
    }

    /**
     * 🔥 AUTO-FILL RENTAL RATE BASED ON BRAND
     */
    public function updated($name, $value)
    {
        // Detect only brand updates
        if (!str_ends_with($name, '.brand')) {
            return;
        }

        $index = explode('.', $name)[1];
        $brand = strtoupper(trim($this->items[$index]['brand'] ?? ''));

        if ($brand === '') {
            $this->items[$index]['rental_rate_per_month'] = null;
            return;
        }

        match ($brand) {
            'HP' => $this->items[$index]['rental_rate_per_month'] = 3000.00,
            'LENOVO' => $this->items[$index]['rental_rate_per_month'] = 3500.00,
            default => null,
        };
    }

    /**
     * Save the IT Leasing items
     */
    public function save()
    {
        try {
            $this->validate([
                'items' => 'required|array|min:1',
                'items.*.category' => 'required|string|max:255',
                'items.*.item_name' => 'required|string|max:255',
                'items.*.serial_number' => 'required|string|unique:it_leasings,serial_number',
                'items.*.charger_serial_number' => 'nullable|string|max:255|unique:it_leasings,charger_serial_number',
                'items.*.brand' => 'nullable|string|max:255',
                'items.*.model' => 'nullable|string|max:255',
                'items.*.purchase_cost' => 'nullable|numeric',
                'items.*.rental_rate_per_month' => 'nullable|numeric',
                'items.*.supplier' => 'nullable|string|max:255',
                'items.*.purchase_order_no' => 'nullable|string|max:255',
                'items.*.purchase_date' => 'nullable|date',
                'items.*.warranty_expiration' => 'nullable|date',
                'items.*.assigned_company' => 'required|string|max:255',
                'items.*.assigned_employee' => 'nullable|string|max:255',
                'items.*.location' => 'nullable|string|max:255',
                'items.*.status' => 'required|in:available,deployed,in_repair,returned,lost',
                'items.*.condition' => 'nullable|in:new,good,fair,poor',
                'items.*.remarks' => 'nullable|string',
                'items.*.inclusions' => 'nullable|array',
                'items.*.inclusions.*' => 'nullable|string|max:255',
            ]);

            foreach ($this->items as $item) {
                ItLeasing::create($item);
            }

            session()->flash('toast', [
                'message' => 'IT Leasing items created successfully!',
                'type' => 'success',
            ]);

            $this->redirect(route('it-leasing.index'), navigate: true);

        } catch (ValidationException $e) {
            $this->dispatch('toast', message: 'Please check required fields.', type: 'error');
            throw $e;

        } catch (QueryException $e) {
            logger()->error('Database error creating IT Leasing', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Database error occurred.', type: 'error');

        } catch (Throwable $e) {
            logger()->error('Unexpected error in ItLeasingCreate', ['error' => $e->getMessage()]);
            $this->dispatch('toast', message: 'Unexpected error occurred.', type: 'error');
        }
    }

    /**
     * Render the component view
     */
    public function render()
    {
        return view('livewire.pages.it-leasing.it-leasing-create');
    }
}
