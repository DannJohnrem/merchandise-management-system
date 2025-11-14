<?php

namespace App\Livewire\Pages\ItLeasing;

use Livewire\Component;
use App\Models\ItLeasing;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Throwable;
use Livewire\WithFileUploads;

class ItLeasingEdit extends Component
{
    use WithFileUploads;

    public $item; // Model binding

    public $category;
    public $serial_number;
    public $brand;
    public $model;
    public $cost;
    public $assigned_to;
    public $class;
    public $status;
    public $qr_code_path;
    public $qrPreview;
    public $remarks;

    public function mount(ItLeasing $item)
    {
        $this->item = $item;

        // Fill form fields
        $this->category = $item->category;
        $this->serial_number = $item->serial_number;
        $this->brand = $item->brand;
        $this->model = $item->model;
        $this->cost = $item->cost;
        $this->assigned_to = $item->assigned_to;
        $this->class = $item->class;
        $this->status = $item->status;
        $this->qrPreview = $item->qr_code_path ? asset('storage/' . $item->qr_code_path) : null;
        $this->remarks = $item->remarks;
    }

    public function update()
    {
        try {
            $validated = $this->validate([
                'category' => 'required|string|max:255',
                'serial_number' => "required|string|unique:it_leasings,serial_number,{$this->item->id}",
                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'cost' => 'nullable|numeric',
                'assigned_to' => 'nullable|string|max:255',
                'class' => 'nullable|string|max:255',
                'status' => 'required|in:in_use,returned,repair',
                'qr_code_path' => 'nullable|file|mimes:jpg,png,pdf',
                'remarks' => 'nullable|string',
            ]);

            // Handle QR code file
            if ($this->qr_code_path) {
                $validated['qr_code_path'] = $this->qr_code_path->store('it_leasing_qrcodes');
            }

            $this->item->update($validated);

            session()->flash('toast', [
                'message' => 'IT Leasing item updated successfully!',
                'type' => 'success',
            ]);

            $this->redirect(route('pages.it-leasing.index'), navigate: true);

        } catch (ValidationException $e) {
            $this->dispatch('toast', message: 'Please check the required fields.', type: 'error');
            throw $e;

        } catch (QueryException $e) {
            $this->dispatch('toast', message: 'Database error occurred while updating item.', type: 'error');
            logger()->error('Database error updating IT Leasing', ['error' => $e->getMessage()]);

        } catch (Throwable $e) {
            $this->dispatch('toast', message: 'Unexpected error. Please try again.', type: 'error');
            logger()->error('Unexpected error in ItLeasingEdit', ['error' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.pages.it-leasing.it-leasing-edit');
    }
}
