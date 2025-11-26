<?php

namespace App\Livewire\Admin\Class;

use Livewire\Component;
use App\Models\ClassModel;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Throwable;

class ClassEdit extends Component
{
    public $classId;

    public $name;
    public $description;
    public $type;
    public $code;
    public $department;
    public $position;

    public function mount(ClassModel $class)
    {
        $this->classId = $class->id;
        $this->name = $class->name;
        $this->description = $class->description;
        $this->type = $class->type;
        $this->code = $class->code;
        $this->department = $class->department;
        $this->position = $class->position;
    }

    public function update()
    {
        try {
            $validated = $this->validate([
                'name' => 'required|string|max:255',
                'type' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'code' => 'nullable|string|max:255',
                'department' => 'nullable|string|max:255',
                'position' => 'nullable|string|max:255',
            ]);

            $class = ClassModel::findOrFail($this->classId);

            $class->update($validated);

            session()->flash('toast', [
                'message' => 'Class updated successfully!',
                'type' => 'success',
            ]);

            $this->redirect(route('admin.class.index'), navigate: true);

        } catch (ValidationException $e) {
            $this->dispatch('toast', message: 'Please check required fields.', type: 'error');
            throw $e;

        } catch (QueryException $e) {
            $this->dispatch('toast', message: 'Database error occurred.', type: 'error');
            logger()->error('DB Error in ClassEdit', ['error' => $e->getMessage()]);

        } catch (Throwable $e) {
            $this->dispatch('toast', message: 'Unexpected error occurred.', type: 'error');
            logger()->error('Error in ClassEdit', ['error' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.admin.class.class-edit');
    }
}
