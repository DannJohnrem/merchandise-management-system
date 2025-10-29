<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserEdit extends Component
{
    public $userId;
    public $name;
    public $email;
    public $password;
    public $role;
    public $roles;

    public function mount(User $user)
    {
        // dd($user);
        $this->userId = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->getRoleNames()->first();
        $this->roles = Role::pluck('name')->toArray();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'password' => 'nullable|min:6',
            'role' => 'required|string',
        ]);

        $user = User::findOrFail($this->userId);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password ? Hash::make($this->password) : $user->password,
        ]);

        $user->syncRoles([$this->role]);

        $this->dispatch('toast', [
            'message' => 'User updated successfully!',
            'type' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.users.user-edit');
    }
}
