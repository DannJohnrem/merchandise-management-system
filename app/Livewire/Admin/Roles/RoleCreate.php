<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleCreate extends Component
{
    public $name = '';
    public $permissions = [];
    public $selectedPermissions = [];

    public $roles;

    protected $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
    ];

    public function mount()
    {
        $this->loadRoles();
        $this->permissions = Permission::orderBy('name')->get();
    }

    public function loadRoles()
    {
        $this->roles = Role::with('permissions')->orderBy('name')->get();
    }

    public function save()
    {
        $this->validate();

        $role = Role::create(['name' => $this->name]);
        $role->syncPermissions($this->selectedPermissions);

        $this->reset(['name', 'selectedPermissions']);
        $this->loadRoles();

        $this->dispatchBrowserEvent('toast', [
            'type' => 'success',
            'message' => 'Role created successfully!',
        ]);
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        $this->loadRoles();

        $this->dispatchBrowserEvent('toast', [
            'type' => 'success',
            'message' => 'Role deleted successfully!',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.roles.role-create');
    }
}
