<div x-on:open-inventory-detail-modal.window="Flux.modal('inventory-item-detail').show()">
    <flux:modal name="inventory-item-detail" class="w-full max-w-4xl">
        <div class="relative min-h-[200px]">

            <div
                wire:loading
                class="absolute left-1/2 top-1/2 z-10 -translate-x-1/2 -translate-y-1/2"
            >
                <span class="text-sm text-zinc-400">Loading item details…</span>
            </div>

            <div wire:loading.remove class="space-y-4">
                <div>
                    <flux:heading size="lg">{{ $name ?: '—' }}</flux:heading>
                    <flux:subheading>
                        {{ $brand }} {{ $model }} @if($category) • {{ $category }} @endif
                    </flux:subheading>
                </div>

                @if ($itemKey)
                    <livewire:modals.inventory-units-panel
                        :source="$source"
                        :item-key="$itemKey"
                        :key="'units-panel-' . $itemKey"
                    />
                @endif
            </div>

        </div>
    </flux:modal>
</div>
