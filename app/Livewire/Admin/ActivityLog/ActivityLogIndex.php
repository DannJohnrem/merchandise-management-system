<?php

namespace App\Livewire\Admin\ActivityLog;

use Livewire\Component;

class ActivityLogIndex extends Component
{
    public bool $readyToLoad = false;

    public function load(): void
    {
        $this->readyToLoad = true;
    }

    public function render()
    {
        return view('livewire.admin.activity-log.activity-log-index');
    }
}
