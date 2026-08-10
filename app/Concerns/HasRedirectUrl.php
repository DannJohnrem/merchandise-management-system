<?php

namespace App\Concerns;

trait HasRedirectUrl
{
    public string $redirectUrl;

    public function bootHasRedirectUrl(): void
    {
        $this->redirectUrl = request()->query('redirect') ?? route('it-leasing.index');
    }
}
