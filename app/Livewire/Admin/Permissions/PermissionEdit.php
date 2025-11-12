<?php

namespace App\Livewire\Admin\Permissions;

use Throwable;
use Livewire\Component;
use App\Models\Permission;
use Illuminate\Database\QueryException;
use App\Models\Permission as ModelsPermission;
use Illuminate\Validation\ValidationException;

class PermissionEdit extends Component
{
    public $permissionId;
    public $name = '';

    public function mount(Permission $permission)
    {
        $this->permissionId = $permission->id;
        $this->name = $permission->name;
    }

    public function update()
    {
        try {
            // Validate input
            $validated = $this->validate([
                'name' => 'required|string|max:255|unique:permissions,name,' . $this->permissionId,
            ]);

            // Update permission
            $permission = Permission::findOrFail($this->permissionId);
            $permission->update(['name' => $validated['name']]);

            // Toast success
            session()->flash('toast', [
                'message' => 'Permission updated successfully!',
                'type' => 'success',
            ]);

            // SPA redirect
            $this->redirect(route('admin.permissions.index'), navigate: true);

        } catch (ValidationException $e) {
            $this->dispatch('toast', message: 'Please check the required fields.', type: 'error');
            throw $e;

        } catch (QueryException $e) {
            $this->dispatch('toast', message: 'Database error occurred while updating permission.', type: 'error');
            logger()->error('Database error while updating permission', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

        } catch (Throwable $e) {
            $this->dispatch('toast', message: 'An unexpected error occurred. Please try again.', type: 'error');
            logger()->error('Unexpected error in PermissionEdit', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.permissions.permission-edit');
    }
}
