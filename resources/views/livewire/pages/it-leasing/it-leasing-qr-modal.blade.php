<flux:modal name="it-leasing-qr" class="md:w-96" :closable="false" :dismissible="false">
    <div class="space-y-6">

        {{-- Logo at the top --}}
        <div class="flex justify-center">
            <img src="{{ asset('apple-touch-icon.png') }}" alt="Logo" class="h-16 w-16 object-contain">
        </div>

        {{-- QR Code --}}
        <div class="flex justify-center items-center min-h-[200px]">
            @if ($isLoading)
                <svg class="animate-spin h-6 w-6 text-gray-500"
                     xmlns="http://www.w3.org/2000/svg" fill="none"
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

        {{-- Item Info Below QR --}}
        @if ($item)
            <div class="space-y-1 text-first mt-2">
                <flux:text>
                    <strong>Category:</strong> {{ $item->category }}
                </flux:text>
                <flux:text>
                    <strong>Brand:</strong> {{ $item->brand ?? '-' }}
                </flux:text>
                <flux:text>
                    <strong>Model:</strong> {{ $item->model ?? '-' }}
                </flux:text>
            </div>
        @endif

        <div class="flex justify-end mt-4">
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
        </div>
    </div>
</flux:modal>
