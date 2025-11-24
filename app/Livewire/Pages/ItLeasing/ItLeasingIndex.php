<?php

namespace App\Livewire\Pages\ItLeasing;

use Livewire\Component;

class ItLeasingIndex extends Component
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
        return view('livewire.pages.it-leasing.it-leasing-index');
    }
}
