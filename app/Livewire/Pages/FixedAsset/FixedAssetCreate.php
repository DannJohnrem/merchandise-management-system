<?php

namespace App\Livewire\Pages\FixedAsset;

use Livewire\Component;
use App\Models\FixedAsset;
use App\Models\ClassModel;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Throwable;

class FixedAssetCreate extends Component
{
    /**
     * Items being created in the form.
     *
     * Each item is an associative array representing a fixed asset payload
     * that will be validated and stored.
     *
     * @var array<int, array<string, mixed>>
     */
    public array $items = [];

    /**
     * Cached list of classes for dropdowns.
     *
     * Loaded in `mount()` to populate the class select control.
     *
     * @var \Illuminate\Database\Eloquent\Collection|\App\Models\ClassModel[]
     */
    public $classes = [];

    /**
     * Initialize component state.
     *
     * Adds one blank item to the form and loads the available classes
     * used by select controls.
     *
     * @return void
     */
    public function mount()
    {
        $this->items[] = $this->blankItem();

        // Load classes for dropdowns
        $this->classes = ClassModel::orderBy('name')->get();
    }

    protected function blankItem(): array
    {
        return [
            'asset_tag' => null,
            'category' => null,
            'asset_name' => null,
            'serial_number' => null,
            'charger_serial_number' => null,
            'brand' => null,
            'model' => null,
            'purchase_cost' => null,
            'supplier' => null,
            'assigned_employee' => null,
            'asset_class' => null,
            'location' => null,
            'status' => 'available',
            'condition' => 'new',
            'purchase_date' => null,
            'purchase_order_no' => null,
            'warranty_expiration' => null,
            'remarks' => null,
            'inclusions' => [],
        ];
    }

    /**
     * Add a new blank item to the items array.
     *
     * @return void
     */
    public function addItem()
    {
        $this->items[] = $this->blankItem();
    }

    /**
     * Remove the item at the given index from the items array.
     *
     * @param int $index
     * @return void
     */
    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    /**
     * Add an empty inclusion entry for a specific item.
     *
     * @param int $itemIndex Index of the item in the `items` array
     * @return void
     */
    public function addInclusion($itemIndex)
    {
        $this->items[$itemIndex]['inclusions'][] = '';
    }

    /**
     * Remove an inclusion at the given index for a specific item.
     *
     * @param int $itemIndex Index of the item in the `items` array
     * @param int $inclusionIndex Index of the inclusion to remove
     * @return void
     */
    public function removeInclusion($itemIndex, $inclusionIndex)
    {
        unset($this->items[$itemIndex]['inclusions'][$inclusionIndex]);
        $this->items[$itemIndex]['inclusions'] = array_values($this->items[$itemIndex]['inclusions']);
    }

    /**
     * Validate and persist the fixed asset items to the database.
     *
     * Validates the `items` array and creates a `FixedAsset` record
     * for each item. Provides user feedback via session toasts and
     * handles database and unexpected errors gracefully.
     *
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function save()
    {
        try {
            $this->validate([
                'items' => 'required|array|min:1',
                'items.*.asset_name' => 'required|string|max:255',
                'items.*.category' => 'required|string|max:255',
                'items.*.asset_tag' => 'nullable|string|max:255',
                'items.*.serial_number' => 'nullable|string|max:255|unique:fixed_assets,serial_number',
                'items.*.charger_serial_number' => 'nullable|string|max:255|unique:fixed_assets,charger_serial_number',
                'items.*.brand' => 'nullable|string|max:255',
                'items.*.model' => 'nullable|string|max:255',
                'items.*.purchase_cost' => 'nullable|numeric',
                'items.*.supplier' => 'nullable|string|max:255',
                'items.*.assigned_employee' => 'nullable|string|max:255',
                'items.*.asset_class' => 'nullable|string|max:255',
                'items.*.location' => 'nullable|string|max:255',
                'items.*.status' => 'nullable|in:available,issued,repair,disposed,lost',
                'items.*.condition' => 'nullable|in:new,good,fair,poor',
                'items.*.purchase_date' => 'nullable|date',
                'items.*.purchase_order_no' => 'nullable|string|max:255',
                'items.*.warranty_expiration' => 'nullable|date',
                'items.*.remarks' => 'nullable|string',
                'items.*.inclusions' => 'nullable|array',
                'items.*.inclusions.*' => 'nullable|string|max:255',
            ]);

            foreach ($this->items as $item) {
                // Encode inclusions array to JSON to prevent DB error
                $item['inclusions'] = json_encode($item['inclusions'] ?? []);

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

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.pages.fixed-asset.fixed-asset-create');
    }
}
