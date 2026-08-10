<?php

namespace App\Livewire\Pages\ItLeasing;

use Throwable;
use Livewire\Component;
use App\Models\ItLeasing;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use App\Concerns\HasRedirectUrl;

class ItLeasingCreate extends Component
{
    use HasRedirectUrl;

    public array $items = [];

    public function mount()
    {
        $this->items[] = $this->blankItem();
    }

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

    protected function validationRules(): array
    {
        return [
            'items'                          => 'required|array|min:1',
            'items.*.category'               => 'required|string|max:255',
            'items.*.item_name'              => 'required|string|max:255',
            'items.*.serial_number'          => 'required|string|max:255',
            'items.*.charger_serial_number'  => 'nullable|string|max:255',
            'items.*.brand'                  => 'nullable|string|max:255',
            'items.*.model'                  => 'nullable|string|max:255',
            'items.*.purchase_cost'          => 'nullable|numeric',
            'items.*.rental_rate_per_month'  => 'nullable|numeric',
            'items.*.supplier'               => 'nullable|string|max:255',
            'items.*.purchase_order_no'      => 'nullable|string|max:255',
            'items.*.purchase_date'          => 'nullable|date',
            'items.*.warranty_expiration'    => 'nullable|date',
            'items.*.assigned_company'       => 'required|string|max:255',
            'items.*.assigned_employee'      => 'nullable|string|max:255',
            'items.*.location'               => 'nullable|string|max:255',
            'items.*.status'                 => 'required|in:available,deployed,in_repair,returned,lost',
            'items.*.condition'              => 'nullable|in:new,good,fair,poor',
            'items.*.remarks'                => 'nullable|string',
            'items.*.inclusions'             => 'nullable|array',
            'items.*.inclusions.*'           => 'nullable|string|max:255',
        ];
    }

    public function addItem()
    {
        $this->items[] = $this->blankItem();
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function addInclusion($itemIndex)
    {
        $this->items[$itemIndex]['inclusions'][] = '';
    }

    public function removeInclusion($itemIndex, $inclusionIndex)
    {
        unset($this->items[$itemIndex]['inclusions'][$inclusionIndex]);
        $this->items[$itemIndex]['inclusions'] = array_values($this->items[$itemIndex]['inclusions']);
    }

    public function updated($name, $value)
    {
        if (!str_ends_with($name, '.brand')) return;

        $index = explode('.', $name)[1];
        $brand = strtoupper(trim($this->items[$index]['brand'] ?? ''));

        if (!empty($this->items[$index]['rental_rate_per_month'])) return;

        match ($brand) {
            'HP'     => $this->items[$index]['rental_rate_per_month'] = 3000.00,
            'LENOVO' => $this->items[$index]['rental_rate_per_month'] = 3500.00,
            default  => null,
        };
    }

    public function copyItem($index)
    {
        $itemToCopy = $this->items[$index];
        $newItem = $itemToCopy;
        $newItem['serial_number'] = null;
        $newItem['charger_serial_number'] = null;
        $this->items[] = $newItem;
    }

    public function save()
    {
        try {
            $this->validate($this->validationRules());

            $serialNumbers = collect($this->items)->pluck('serial_number');
            $chargerSerials = collect($this->items)
                ->pluck('charger_serial_number')
                ->filter()
                ->values();

            if ($serialNumbers->unique()->count() !== $serialNumbers->count()) {
                $this->dispatch('toast', message: 'Duplicate serial numbers found within the batch.', type: 'error');
                return;
            }

            if ($chargerSerials->unique()->count() !== $chargerSerials->count()) {
                $this->dispatch('toast', message: 'Duplicate charger serial numbers found within the batch.', type: 'error');
                return;
            }

            $existingSerials = ItLeasing::whereIn('serial_number', $serialNumbers)
                ->pluck('serial_number');

            if ($existingSerials->isNotEmpty()) {
                $this->dispatch('toast', message: 'Serial number(s) already exist in the database: ' . $existingSerials->join(', '), type: 'error');
                return;
            }

            if ($chargerSerials->isNotEmpty()) {
                $existingChargers = ItLeasing::whereIn('charger_serial_number', $chargerSerials)
                    ->pluck('charger_serial_number');

                if ($existingChargers->isNotEmpty()) {
                    $this->dispatch('toast', message: 'Charger serial number(s) already exist in the database: ' . $existingChargers->join(', '), type: 'error');
                    return;
                }
            }

            foreach ($this->items as $item) {
                $item['inclusions'] = $item['inclusions'] ?? [];
                ItLeasing::create($item);
            }

            Cache::forget('it_leasing_categories');
            Cache::forget('it_leasing_serial_numbers');

            session()->flash('toast', [
                'message' => 'IT Leasing items created successfully!',
                'type'    => 'success',
            ]);

            $this->redirect($this->redirectUrl, navigate: true);

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

    public function render()
    {
        return view('livewire.pages.it-leasing.it-leasing-create');
    }
}
