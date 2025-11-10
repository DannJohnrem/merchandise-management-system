<?php

namespace App\Livewire\Admin\Roles;

use Throwable;
use App\Models\Role;
use Livewire\Component;
use App\Models\Permission;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class RoleCreate extends Component
{
    public $name = '';
    public $selectedPermissions = [];

    public function save()
    {
        try {
            // Validate input
            $validated = $this->validate([
                'name' => 'required|string|max:255|unique:roles,name',
                'selectedPermissions' => 'array',
                'selectedPermissions.*' => 'exists:permissions,name',
            ]);

            // Create role
            $role = Role::create(['name' => $validated['name']]);

            // Assign permissions if any
            if (!empty($validated['selectedPermissions'])) {
                $role->syncPermissions($validated['selectedPermissions']);
            }

            // Store toast in session so it shows after redirect
            session()->flash('toast', [
                'message' => 'Role updated successfully!',
                'type' => 'success',
            ]);

            // SPA redirect — Livewire v3 compatible
            $this->redirect(route('admin.roles.index'), navigate: true);

        } catch (ValidationException $e) {
            $this->dispatch('toast', message: 'Please check the required fields.', type: 'error');
            throw $e;

        } catch (QueryException $e) {
            $this->dispatch('toast', message: 'Database error occurred while saving role.', type: 'error');
            logger()->error('Database error while creating role', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

        } catch (Throwable $e) {
            $this->dispatch('toast', message: 'An unexpected error occurred. Please try again.', type: 'error');
            logger()->error('Unexpected error in RoleCreate', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.roles.role-create', [
            // 'permissions' => Permission::orderBy('name')->pluck('name')->toArray(),
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }
}
