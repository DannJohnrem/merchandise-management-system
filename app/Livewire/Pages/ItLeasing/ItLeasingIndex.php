<?php

namespace App\Livewire\Pages\ItLeasing;

use Livewire\Component;

class ItLeasingIndex extends Component
{
    public int $currentPage = 1;
    public float $pageTotal = 0;
    public float $grandTotal = 0;

    protected $listeners = [
        'totalsUpdated' => 'updateTotals',
    ];

    /**
     * Receive updated totals from the child table component.
     *
     * Updates the component's `pageTotal` and `grandTotal` properties
     * with the values provided by the `ItLeasingTable` component.
     *
     * @param array{pageTotal: float|int,grandTotal: float|int} $totals
     * @return void
     */
    public function updateTotals(array $totals): void
    {
        $this->pageTotal  = (float) $totals['pageTotal'];
        $this->grandTotal = (float) $totals['grandTotal'];
        $this->currentPage = (int) $totals['currentPage'];
    }

    /**
     * Component mount hook.
     *
     * If a session toast exists (from a previous action), dispatch it
     * so the UI shows the message when the page loads.
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
