<?php

namespace App\Livewire\Pages\FixedAsset;

use Livewire\Component;
use App\Models\FixedAsset;

class FixedAssetShow extends Component
{
    public FixedAsset $asset;

    public function mount(FixedAsset $asset)
    {
        $this->asset = $asset;
    }

    public function render()
    {
        return view('livewire.pages.fixed-asset.fixed-asset-show');
    }
}
