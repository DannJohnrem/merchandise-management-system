<div x-on:open-inventory-detail-modal.window="Flux.modal('inventory-item-detail').show()">
    <flux:modal name="inventory-item-detail" class="w-full max-w-4xl">

        <div wire:loading.delay wire:target="open" class="py-16 text-center text-sm text-zinc-400">
            Loading item details…
        </div>

        <div wire:loading.remove.delay wire:target="open" wire:key="inventory-detail-{{ $itemKey ?: 'empty' }}" class="space-y-4">
            <div>
                <flux:heading size="lg">{{ $name ?: '—' }}</flux:heading>
                <flux:subheading>
                    {{ $brand }} {{ $model }} @if($category) • {{ $category }} @endif
                </flux:subheading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- AVAILABLE --}}
                <div class="border rounded-lg p-3">
                    <div class="flex items-center justify-between mb-2">
                        <flux:heading size="sm">Available</flux:heading>
                        <flux:badge color="green">{{ count($availableUnits) }}</flux:badge>
                    </div>

                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @forelse ($availableUnits as $unit)
                            <div wire:key="avail-{{ $itemKey }}-{{ $unit['id'] ?? $loop->index }}" class="border rounded p-2 text-sm">
                                <div class="text-zinc-500">SN: {{ $unit['serial_number'] ?: 'N/A' }}</div>
                                @if(!empty($unit['charger_serial_number']))
                                    <div class="text-zinc-500">Charger SN: {{ $unit['charger_serial_number'] }}</div>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-zinc-400">No available units.</p>
                        @endforelse
                    </div>
                </div>

                {{-- DEPLOYED --}}
                <div class="border rounded-lg p-3">
                    <div class="flex items-center justify-between mb-2">
                        <flux:heading size="sm">Deployed</flux:heading>
                        <flux:badge color="amber">{{ count($deployedUnits) }}</flux:badge>
                    </div>

                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @forelse ($deployedUnits as $unit)
                            <div wire:key="deployed-{{ $itemKey }}-{{ $unit['id'] ?? $loop->index }}" class="border rounded p-2 text-sm">
                                <div class="text-zinc-500">SN: {{ $unit['serial_number'] ?: 'N/A' }}</div>
                                @if(!empty($unit['charger_serial_number']))
                                    <div class="text-zinc-500">Charger SN: {{ $unit['charger_serial_number'] }}</div>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-zinc-400">No deployed units.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </flux:modal>
</div>
