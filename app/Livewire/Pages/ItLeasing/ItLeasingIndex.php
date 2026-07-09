<?php

namespace App\Livewire\Pages\ItLeasing;

use Livewire\Component;
use Livewire\Attributes\On;

class ItLeasingIndex extends Component
{
    public bool $readyToLoad = false;
    public int $currentPage = 1;
    public float $pageTotal = 0;
    public float $grandTotal = 0;

    public function load(): void
    {
        $this->readyToLoad = true;
    }

    /**
     * Receive updated totals from the child table component.
     *
     * grandTotal is only sent on page 1. On other pages, the table sends 0
     * to avoid an expensive query — so we preserve the last known grandTotal
     * instead of overwriting it with 0.
     *
     * @param array{pageTotal: float, grandTotal: float, currentPage: int} $totals
     * @return void
     */
    #[On('totalsUpdated')]
    public function updateTotals(array $totals): void
    {
        $this->currentPage = (int) $totals['currentPage'];
        $this->pageTotal   = (float) $totals['pageTotal'];

        // Only update grandTotal when the table actually computed it (page 1).
        // On page 2+, the table sends 0 — preserve the last known value instead.
        if ($this->currentPage === 1) {
            $this->grandTotal = (float) $totals['grandTotal'];
        }
    }

    /**
     * Component mount hook.
     *
     * If a session toast exists (from a previous action like edit/create),
     * dispatch it so the UI shows the message when the page loads.
     *
     * @return void
     */
    public function mount(): void
    {
        if (session('toast')) {
            $toast = session('toast');
            $this->dispatch('toast', message: $toast['message'], type: $toast['type']);
        }
    }

    /**
     * Render the index view for IT Leasing.
     *
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.pages.it-leasing.it-leasing-index');
    }
}
