<div>
    <flux:modal name="generate-dr" class="w-full max-w-lg">

        {{-- Header --}}
        <div class="mb-5">
            <flux:heading size="lg">Generate Delivery Receipt</flux:heading>
        </div>

        {{-- SHIPPED TO --}}
        <div class="mb-4">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Shipped To</p>
            <div class="space-y-2">
                <flux:field>
                    <flux:label>Company Name</flux:label>
                    <flux:input wire:model="shippedToCompany" placeholder="e.g. BTSMC Managing Solutions, Inc." />
                    <flux:error name="shippedToCompany" />
                </flux:field>

                <flux:field>
                    <flux:label>Address</flux:label>
                    <flux:textarea wire:model="shippedToAddress" rows="2"
                        placeholder="Unit, Building, Street, City" />
                    <flux:error name="shippedToAddress" />
                </flux:field>
            </div>
        </div>

        {{-- BILLED TO --}}
        <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Billed To</p>
                <flux:field variant="inline">
                    <flux:checkbox wire:model.live="billedSameAsShipped" />
                    <flux:label>Same as Shipped To</flux:label>
                </flux:field>
            </div>

            @if (!$billedSameAsShipped)
                <div class="space-y-2">
                    <flux:field>
                        <flux:label>Company Name</flux:label>
                        <flux:input wire:model="billedToCompany" placeholder="e.g. Another Company, Inc." />
                        <flux:error name="billedToCompany" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Address</flux:label>
                        <flux:textarea wire:model="billedToAddress" rows="2"
                            placeholder="Unit, Building, Street, City" />
                        <flux:error name="billedToAddress" />
                    </flux:field>
                </div>
            @else
                <p class="text-xs text-gray-400 italic">Will use the same details as Shipped To.</p>
            @endif
        </div>

        {{-- SIGNATORIES --}}
        <div class="mb-6">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Signatories</p>
            <div class="grid grid-cols-2 gap-3">
                <flux:field>
                    <flux:label>Released By</flux:label>
                    <flux:input wire:model="releasedBy" />
                    <flux:error name="releasedBy" />
                </flux:field>

                <flux:field>
                    <flux:label>Received By</flux:label>
                    <flux:input wire:model="receivedBy" placeholder="e.g. BTSMC Managing Solutions" />
                    <flux:error name="receivedBy" />
                </flux:field>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>

            <flux:button wire:click="generate" wire:loading.attr="disabled" variant="primary">
                <span wire:loading.remove wire:target="generate">Generate PDF</span>
                <span wire:loading wire:target="generate">Generating...</span>
            </flux:button>
        </div>

    </flux:modal>

    {{-- Opens PDF in new tab --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('open-dr-window', ({
                url
            }) => {
                window.open(url, '_blank');
            });
        });
    </script>
</div>
