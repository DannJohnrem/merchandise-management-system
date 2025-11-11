<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Throwable;

class UserCreate extends Component
{
    public $name;
    public $email;
    public $password;
    public $role = '';

    public function save()
    {
        try {
            // Validate form inputs
            $validated = $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'role' => 'required|string|exists:roles,name',
            ]);

            // Create user record
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Assign role
            $user->assignRole($validated['role']);

            // Store toast in session so it shows after redirect
            session()->flash('toast', [
                'message' => 'User created successfully!',
                'type' => 'success',
            ]);

            // SPA redirect — Livewire v3 compatible
            $this->redirect(route('admin.users.index'), navigate: true);

        } catch (ValidationException $e) {
            $this->dispatch('toast', message: 'Please check the required fields.', type: 'error');
            throw $e;

        } catch (QueryException $e) {
            $this->dispatch('toast', message: 'Database error occurred while saving user.', type: 'error');
            logger()->error('Database error while creating user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

        } catch (Throwable $e) {
            $this->dispatch('toast', message: 'An unexpected error occurred. Please try again.', type: 'error');
            logger()->error('Unexpected error in UserCreate', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.users.user-create', [
            'roles' => Role::pluck('name')->toArray(),
        ]);
    }
}
