<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserCreate extends Component
{
    public $name;
    public $email;
    public $password;
    public $role = '';

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $user->assignRole($this->role);

        // 🔔 Trigger global toast
        $this->dispatch('toast', [
            'message' => 'User created successfully!',
            'type' => 'success',
        ]);

        $this->reset(['name', 'email', 'password', 'role']);
    }

    public function render()
    {
        return view('livewire.admin.users.user-create', [
            'roles' => Role::pluck('name')->toArray(),
        ]);
    }
}
