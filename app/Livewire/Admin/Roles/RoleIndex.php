<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;

class RoleIndex extends Component
{
    public function mount()
    {
        // Check for flashed toast from redirects (e.g., RoleEdit or RoleCreate)
        if (session('toast')) {
            $toast = session('toast');
            $this->dispatch('toast', message: $toast['message'], type: $toast['type']);
        }
    }

    public function render()
    {
        return view('livewire.admin.roles.role-index');
    }
}
