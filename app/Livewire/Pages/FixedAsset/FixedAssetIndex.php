<?php

namespace App\Livewire\Pages\FixedAsset;

use Livewire\Component;

class FixedAssetIndex extends Component
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
        return view('livewire.pages.fixed-asset.fixed-asset-index');
    }
}
