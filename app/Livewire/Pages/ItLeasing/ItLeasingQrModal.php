<?php

namespace App\Livewire\Pages\ItLeasing;

use Livewire\Component;
use App\Models\ItLeasing;
use Endroid\QrCode\Builder\Builder;

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

    // open flux modal
    $this->dispatchBrowserEvent('modal:open', ['name' => 'it-leasing-qr']);

    $this->loadQrCode($itemId);
}


    private function loadQrCode($id)
    {
        $this->item = ItLeasing::find($id);

        if (!$this->item) {
            $this->isLoading = false;
            return;
        }

        $this->qrCodeSvg = Builder::create()
            ->data($this->item->serial_number)
            ->size(200)
            ->margin(10)
            ->build()
            ->getString();

        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.pages.it-leasing.it-leasing-qr-modal');
    }
}
