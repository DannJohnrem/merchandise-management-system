<?php

namespace App\Livewire\Pages\FixedAsset;

use Livewire\Component;

class FixedAssetIndex extends Component
{
    /**
     * Initialize component state.
     *
     * Checks for any toast messages in the session and dispatches them
     * to the frontend for display.
     *
     * @return void
     */
    public function mount()
    {
        if (session('toast')) {
            $toast = session('toast');
            $this->dispatch('toast', message: $toast['message'], type: $toast['type']);
        }
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.pages.fixed-asset.fixed-asset-index');
    }
}
