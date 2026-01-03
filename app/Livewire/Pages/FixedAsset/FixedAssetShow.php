<?php

namespace App\Livewire\Pages\FixedAsset;

use Livewire\Component;
use App\Models\FixedAsset;

class FixedAssetShow extends Component
{
    public FixedAsset $asset;

    /**
     * Initialize component with the given FixedAsset model.
     *
     * @param \App\Models\FixedAsset $asset
     * @return void
     */
    public function mount(FixedAsset $asset)
    {
        $this->asset = $asset;
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.pages.fixed-asset.fixed-asset-show');
    }
}
