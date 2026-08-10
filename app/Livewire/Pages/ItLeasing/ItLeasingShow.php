<?php

namespace App\Livewire\Pages\ItLeasing;

use Livewire\Component;
use App\Models\ItLeasing;
use Illuminate\Support\Facades\Cache;
use App\Concerns\HasRedirectUrl;

class ItLeasingShow extends Component
{
    use HasRedirectUrl;

    public ItLeasing $itLeasing;

    public function mount(ItLeasing $itLeasing)
    {
        $this->itLeasing = $itLeasing;
    }

    public function delete($id)
    {
        $item = ItLeasing::findOrFail($id);
        $name = $item->serial_number ?? 'Item';
        $item->delete();

        Cache::forget('it_leasing_categories');
        Cache::forget('it_leasing_serial_numbers');

        session()->flash('toast', [
            'message' => "{$name} deleted successfully!",
            'type'    => 'success',
        ]);

        return $this->redirect($this->redirectUrl, navigate: true);
    }

    public function render()
    {
        return view('livewire.pages.it-leasing.it-leasing-show');
    }
}
