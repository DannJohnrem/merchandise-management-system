<?php

namespace App\Livewire\Pages\ItLeasing;

use Flux\Flux;
use Livewire\Component;

class GenerateDrModal extends Component
{
    public array $selectedIds = [];

    // Shipped To
    public string $shippedToCompany = '';
    public string $shippedToAddress = '';

    // Billed To
    public bool $billedSameAsShipped = true;
    public string $billedToCompany = '';
    public string $billedToAddress = '';

    // Signatories
    public string $releasedBy = 'ACJ SUMMIT VENTURES CORP';
    public string $receivedBy = '';

    protected $listeners = ['openDrModal' => 'open'];

    protected function rules(): array
    {
        return [
            'shippedToCompany' => 'required|string|max:255',
            'shippedToAddress' => 'required|string|max:500',
            'billedToCompany'  => $this->billedSameAsShipped ? 'nullable' : 'required|string|max:255',
            'billedToAddress'  => $this->billedSameAsShipped ? 'nullable' : 'required|string|max:500',
            'releasedBy'       => 'required|string|max:255',
            'receivedBy'       => 'required|string|max:255',
        ];
    }

    public function open(array $ids): void
    {
        $this->reset([
            'shippedToCompany', 'shippedToAddress',
            'billedToCompany',  'billedToAddress',
            'receivedBy',
        ]);

        $this->selectedIds         = $ids;
        $this->billedSameAsShipped = true;
        $this->releasedBy          = 'ACJ SUMMIT VENTURES CORP';

        Flux::modal('generate-dr')->show();
    }

    public function close(): void
    {
        Flux::modal('generate-dr')->close();
    }

    public function generate(): void
    {
        $this->validate();

        $params = http_build_query([
            'ids'                => implode(',', $this->selectedIds),
            'shipped_to_company' => $this->shippedToCompany,
            'shipped_to_address' => $this->shippedToAddress,
            'billed_to_company'  => $this->billedSameAsShipped ? $this->shippedToCompany : $this->billedToCompany,
            'billed_to_address'  => $this->billedSameAsShipped ? $this->shippedToAddress : $this->billedToAddress,
            'released_by'        => $this->releasedBy,
            'received_by'        => $this->receivedBy,
        ]);

        $url = route('it-leasing.delivery-receipt') . '?' . $params;

        $this->dispatch('open-dr-window', url: $url);
        $this->close();
    }

    public function render()
    {
        return view('livewire.pages.it-leasing.generate-dr-modal');
    }
}
