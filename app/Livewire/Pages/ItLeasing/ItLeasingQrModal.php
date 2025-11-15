<?php

namespace App\Livewire\Pages\ItLeasing;

use Livewire\Component;
use App\Models\ItLeasing;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Encoding\Encoding;

class ItLeasingQrModal extends Component
{
    public $item = null;
    public $qrCodeSvg = null;
    public $isLoading = false;

    protected $listeners = ['show-qr-modal' => 'prepareModal'];

    public function prepareModal($itemId)
    {
        if (!$itemId) return;

        $this->item = null;
        $this->qrCodeSvg = null;
        $this->isLoading = true;

        // Open Flux modal (Livewire v3 dispatch)
        $this->dispatch('flux:modal-open', name: 'it-leasing-qr');

        $this->loadQrCode($itemId);
    }

    private function loadQrCode($id)
    {
        $this->item = ItLeasing::find($id);

        if (!$this->item) {
            $this->isLoading = false;
            return;
        }

        // Public URL that QR will open
        $url = route('it-leasing.show', $this->item->id);

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
        return view('livewire.pages.it-leasing.it-leasing-qr-modal');
    }
}
