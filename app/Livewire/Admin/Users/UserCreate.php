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

            // Success toast
            $this->dispatch('toast', message: 'User created successfully!', type: 'success');

            // Reset form fields
            $this->reset(['name', 'email', 'password', 'role']);

        } catch (ValidationException $e) {
            // Validation errors — let Livewire show inline errors
            $this->dispatch('toast', message: 'Please check the required fields.', type: 'error');
            throw $e;

        } catch (QueryException $e) {
            // Database-level errors (e.g., unique constraint, SQL issues)
            $this->dispatch('toast', message: 'Database error occurred while saving user.', type: 'error');
            logger()->error('Database error while creating user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

        } catch (Throwable $e) {
            // Catch-all for any unexpected errors
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
