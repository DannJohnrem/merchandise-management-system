<?php

namespace App\Livewire\Admin\Class;

use Livewire\Component;
use App\Models\ClassModel;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Throwable;

class ClassCreate extends Component
{
    public $name;
    public $description;
    public $type;
    public $code;
    public $department;
    public $position;

    public function save()
    {
        try {
            // VALIDATION
            $validated = $this->validate([
                'name' => 'required|string|max:255',
                'type' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'code' => 'nullable|string|max:255',
                'department' => 'nullable|string|max:255',
                'position' => 'nullable|string|max:255',
            ]);

            // SAVE TO DATABASE
            ClassModel::create($validated);

            // SUCCESS TOAST
            session()->flash('toast', [
                'message' => 'Class created successfully!',
                'type' => 'success',
            ]);

            // REDIRECT
            $this->redirect(route('admin.class.index'), navigate: true);

        } catch (ValidationException $e) {
            $this->dispatch('toast', message: 'Please check required fields.', type: 'error');
            throw $e;

        } catch (QueryException $e) {
            $this->dispatch('toast', message: 'Database error occurred.', type: 'error');
            logger()->error('DB Error in ClassCreate', ['error' => $e->getMessage()]);

        } catch (Throwable $e) {
            $this->dispatch('toast', message: 'Unexpected error occurred.', type: 'error');
            logger()->error('Error in ClassCreate', ['error' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.admin.class.class-create');
    }
}
