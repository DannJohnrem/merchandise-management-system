<?php

namespace App\Livewire\Admin\Permissions;

use Throwable;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class PermissionCreate extends Component
{
    public $name = '';

    public function save()
    {
        try {
            // Validate input
            $validated = $this->validate([
                'name' => 'required|string|max:255|unique:permissions,name',
            ]);

            // Create permission
            Permission::create(['name' => $validated['name']]);

            // Store toast in session so it shows after redirect
            session()->flash('toast', [
                'message' => 'Permission created successfully!',
                'type' => 'success',
            ]);

            // SPA redirect — Livewire v3 compatible
            $this->redirect(route('admin.permissions.index'), navigate: true);

        } catch (ValidationException $e) {
            $this->dispatch('toast', message: 'Please check the required fields.', type: 'error');
            throw $e;

        } catch (QueryException $e) {
            $this->dispatch('toast', message: 'Database error occurred while saving permission.', type: 'error');
            logger()->error('Database error while creating permission', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

        } catch (Throwable $e) {
            $this->dispatch('toast', message: 'An unexpected error occurred. Please try again.', type: 'error');
            logger()->error('Unexpected error in PermissionCreate', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.permissions.permission-create');
    }
}
