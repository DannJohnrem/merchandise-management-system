<?php

namespace App\Livewire\Pages\FixedAsset;

use Livewire\Component;
use App\Models\FixedAsset;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Encoding\Encoding;

class FixedAssetQrModal extends Component
{
    public $item = null;
    public $qrCodeSvg = null;
    public $isLoading = false;

    protected $listeners = ['show-fixed-asset-qr' => 'prepareModal'];

    public function prepareModal($id)
    {
        if (!$id) return;

        $this->item = null;
        $this->qrCodeSvg = null;
        $this->isLoading = true;

        // Open Flux modal
        $this->dispatch('flux:modal-open', name: 'fixed-asset-qr');

        $this->loadQrCode($id);
    }

    private function loadQrCode($id)
    {
        $this->item = FixedAsset::find($id);

        if (!$this->item) {
            $this->isLoading = false;
            return;
        }

        // Public URL that QR will open (you can adjust to signed route if needed)
        $url = route('fixed-asset.show.qr', $this->item->id);

        $qrCode = new QrCode(
            data: $url,
            encoding: new Encoding('UTF-8'),
            size: 200,
            margin: 10
        );

        $writer = new SvgWriter();
        $result = $writer->write($qrCode);

        $this->qrCodeSvg = $result->getString();
        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.pages.fixed-asset.fixed-asset-qr-modal');
    }
}
