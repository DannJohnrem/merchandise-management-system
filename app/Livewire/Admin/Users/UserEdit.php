<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Throwable;

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
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->getRoleNames()->first();
        $this->roles = Role::pluck('name')->toArray();
    }

    public function update()
    {
        try {
            // Validate inputs
            $validated = $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $this->userId,
                'password' => 'nullable|min:6',
                'role' => 'required|string|exists:roles,name',
            ]);

            $user = User::findOrFail($this->userId);

            // Update user
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'] ? Hash::make($validated['password']) : $user->password,
            ]);

            // Sync role
            $user->syncRoles([$validated['role']]);

            // Success toast
            session()->flash('toast', [
                'message' => 'User updated successfully!',
                'type' => 'success',
            ]);

            $this->redirect(route('admin.users.index'), navigate: true);

        } catch (ValidationException $e) {
            $this->dispatch('toast', message: 'Please check the required fields.', type: 'error');
            throw $e;

        } catch (QueryException $e) {
            $this->dispatch('toast', message: 'Database error occurred while updating user.', type: 'error');
            logger()->error('Database error while updating user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

        } catch (Throwable $e) {
            $this->dispatch('toast', message: 'An unexpected error occurred. Please try again.', type: 'error');
            logger()->error('Unexpected error in UserEdit', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.users.user-edit');
    }
}
