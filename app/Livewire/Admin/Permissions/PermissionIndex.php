<?php

namespace App\Livewire\Admin\Permissions;

use Livewire\Component;

class PermissionIndex extends Component
{

    public function mount()
    {
        if (session('toast')) {
            $toast = session('toast');
            $this->dispatch('toast', message: $toast['message'], type: $toast['type']);
        }
    }

    public function render()
    {
        return view('livewire.admin.permissions.permission-index');
    }
}
