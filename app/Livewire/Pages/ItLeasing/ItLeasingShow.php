<?php

namespace App\Livewire\Pages\ItLeasing;

use Livewire\Component;
use App\Models\ItLeasing;

class ItLeasingShow extends Component
{
    public ItLeasing $itLeasing;

    public function mount(ItLeasing $itLeasing)
    {
        $this->itLeasing = $itLeasing;
    }

    public function render()
    {
        return view('livewire.pages.it-leasing.it-leasing-show');
    }
}
