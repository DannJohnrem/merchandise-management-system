<?php

namespace App\Livewire\Admin\Class;

use Livewire\Component;

class ClassIndex extends Component
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
        return view('livewire.admin.class.class-index');
    }
}
