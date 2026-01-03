<?php

namespace App\Livewire\Pages\ItLeasing;

use Livewire\Component;
use App\Models\ItLeasing;

class ItLeasingShow extends Component
{
    /**
     * The ItLeasing model instance to display.
     *
     * @var \App\Models\ItLeasing
     */
    public ItLeasing $itLeasing;

    /**
     * Initialize component with the given ItLeasing model.
     *
     * @param \App\Models\ItLeasing $itLeasing
     * @return void
     */
    public function mount(ItLeasing $itLeasing)
    {
        $this->itLeasing = $itLeasing;
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.pages.it-leasing.it-leasing-show');
    }
}
