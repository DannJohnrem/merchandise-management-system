<div> {{-- SINGLE ROOT --}}

    <flux:modal name="fixed-asset-qr" class="md:w-96" :closable="false" :dismissible="false">
        <div class="space-y-6">

            {{-- PRINT AREA --}}
            <div id="qr-print-area" class="space-y-4">

                <div class="flex justify-center">
                    <img src="{{ asset('apple-touch-icon.png') }}"
                         class="h-16 w-16 object-contain">
                </div>

                <div class="flex justify-center items-center min-h-[200px]">
                    @if ($isLoading)
                        <svg class="animate-spin h-6 w-6 text-gray-500"></svg>
                    @elseif ($qrCodeSvg)
                        {!! $qrCodeSvg !!}
                    @endif
                </div>

                @if ($item)
                    <div class="text-center text-sm">
                        Asset Tag: {{ $item->asset_tag }}
                    </div>
                @endif
            </div>

            {{-- ACTIONS --}}
            <div class="flex justify-end gap-2 print:hidden">
                <flux:button wire:click="printQr" variant="primary">
                    Print QR
                </flux:button>

                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
            </div>
        </div>
    </flux:modal>

    {{-- PRINT-JS LISTENER --}}
    <div wire:ignore>
        <script>
            document.addEventListener('print-qr', () => {
                printJS({
                    printable: 'qr-print-area',
                    type: 'html',
                    scanStyles: true,
                    css: [],
                    style: `
                        body {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            padding: 24px;
                            font-family: sans-serif;
                        }
                        svg {
                            width: 200px;
                            height: 200px;
                        }
                    `
                });
            });
        </script>
    </div>

</div>
