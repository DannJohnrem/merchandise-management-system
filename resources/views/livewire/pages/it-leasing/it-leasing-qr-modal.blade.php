<flux:modal name="it-leasing-qr" class="md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">
                @if ($isLoading)
                    Loading QR...
                @elseif ($item)
                    QR Code for {{ $item->serial_number }}
                @endif
            </flux:heading>

            @if ($item)
                <flux:text class="mt-2">
                    {{ $item->category }} - {{ $item->brand ?? '-' }} {{ $item->model ?? '' }}
                </flux:text>
            @endif
        </div>

        <div class="flex justify-center items-center min-h-[200px]">
            @if ($isLoading)
                <svg class="animate-spin h-6 w-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"
                            stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
            @elseif ($qrCodeSvg)
                {!! $qrCodeSvg !!}
            @endif
        </div>

        <div class="flex justify-end mt-4">
            <flux:modal.close variant="primary">Close</flux:modal.close>
        </div>
    </div>
</flux:modal>
