<?php

namespace App\Livewire\Pages\ItLeasing;

use Livewire\Component;
use App\Models\ItLeasing;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Throwable;
use Livewire\WithFileUploads;

class ItLeasingCreate extends Component
{
    use WithFileUploads;

    public $category = '';
    public $serial_number;
    public $brand;
    public $model;
    public $cost;
    public $assigned_to;
    public $class;
    public $status = 'available';
    public $qr_code_path;
    public $remarks;

    public function save()
    {
        try {
            // Validate input
            $validated = $this->validate([
                'category' => 'required|string|max:255',
                'serial_number' => 'required|string|unique:it_leasings,serial_number',
                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'cost' => 'nullable|numeric',
                'assigned_to' => 'nullable|string|max:255',
                'class' => 'nullable|string|max:255',
                'status' => 'required|in:available,in_use,returned,repair,lost',
                'qr_code_path' => 'nullable|file|mimes:jpg,png,pdf',
                'remarks' => 'nullable|string',
            ]);

            // Handle QR code file
            if ($this->qr_code_path) {
                $validated['qr_code_path'] = $this->qr_code_path->store('it_leasing_qrcodes');
            }

            // Create record
            ItLeasing::create($validated);

            // Show toast and redirect
            session()->flash('toast', [
                'message' => 'IT Leasing item created successfully!',
                'type' => 'success',
            ]);

            $this->redirect(route('it-leasing.index'), navigate: true);

        } catch (ValidationException $e) {
            $this->dispatch('toast', message: 'Please check the required fields.', type: 'error');
            throw $e;

        } catch (QueryException $e) {
            $this->dispatch('toast', message: 'Database error occurred while saving item.', type: 'error');
            logger()->error('Database error creating IT Leasing', ['error' => $e->getMessage()]);

        } catch (Throwable $e) {
            $this->dispatch('toast', message: 'Unexpected error. Please try again.', type: 'error');
            logger()->error('Unexpected error in ItLeasingCreate', ['error' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.pages.it-leasing.it-leasing-create');
    }
}
