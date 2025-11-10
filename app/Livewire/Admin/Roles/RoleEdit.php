<?php

namespace App\Livewire\Admin\Roles;

use Throwable;
use App\Models\Role;
use Livewire\Component;
use App\Models\Permission;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class RoleEdit extends Component
{
    public Role $role;
    public $name = '';
    public $selectedPermissions = [];
    public $permissions;

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->permissions = Permission::orderBy('name')->get();
    }

    public function update()
    {
        try {
            // Validate input
            $validated = $this->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('roles', 'name')->ignore($this->role->id),
                ],
                'selectedPermissions' => 'array',
                'selectedPermissions.*' => 'exists:permissions,name',
            ]);

            // Update role
            $this->role->update(['name' => $validated['name']]);

            // Sync permissions
            $this->role->syncPermissions($validated['selectedPermissions'] ?? []);

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
            $this->dispatch('toast', message: 'Database error occurred while updating role.', type: 'error');
            logger()->error('Database error while updating role', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        } catch (Throwable $e) {
            $this->dispatch('toast', message: 'An unexpected error occurred. Please try again.', type: 'error');
            logger()->error('Unexpected error in RoleEdit', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }


    public function render()
    {
        return view('livewire.admin.roles.role-edit');
    }
}
