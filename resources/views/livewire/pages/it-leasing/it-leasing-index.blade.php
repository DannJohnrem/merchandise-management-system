<div class="space-y-4 w-full">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('it-leasing.index') }}" wire:navigate>
            IT Leasing
        </flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <x-page-heading>
        <x-slot:title>IT Leasing</x-slot:title>
        <x-slot:subtitle>Manage IT leased items here</x-slot:subtitle>
    </x-page-heading>

    <div class="flex justify-end">
        @can('create it-leasing')
            <flux:button
                icon="plus"
                variant="primary"
                onclick="
                    var qs = window.location.search;
                    Livewire.navigate('{{ route('it-leasing.create') }}?redirect=' + encodeURIComponent(window.location.pathname + qs));
                "
            ></flux:button>
        @endcan
    </div>

    <div class="relative w-full border rounded-lg p-4 bg-white dark:bg-zinc-800 shadow-sm">
        <div wire:init="load">

            @if (! $readyToLoad)
                {{-- SKELETON --}}
                <flux:skeleton.group animate="shimmer" class="space-y-4">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2 w-full">
                            <div class="h-10 w-64"><flux:skeleton.line /></div>
                            <div class="h-10 w-40 hidden sm:block"><flux:skeleton.line /></div>
                            <div class="h-10 w-40 hidden md:block"><flux:skeleton.line /></div>
                        </div>
                        <div class="h-10 w-28"><flux:skeleton.line /></div>
                    </div>

                    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
                        <div class="grid grid-cols-12 gap-3 px-4 py-3 bg-zinc-50 dark:bg-zinc-900/40">
                            <div class="col-span-2 h-4"><flux:skeleton.line style="width: 70%" /></div>
                            <div class="col-span-2 h-4"><flux:skeleton.line style="width: 60%" /></div>
                            <div class="col-span-3 h-4"><flux:skeleton.line style="width: 80%" /></div>
                            <div class="col-span-2 h-4 hidden md:block"><flux:skeleton.line style="width: 55%" /></div>
                            <div class="col-span-2 h-4 hidden lg:block"><flux:skeleton.line style="width: 65%" /></div>
                            <div class="col-span-1 h-4"><flux:skeleton.line style="width: 40%" /></div>
                        </div>

                        @foreach (range(1, 8) as $row)
                            <div class="grid grid-cols-12 gap-3 px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
                                <div class="col-span-2 h-4"><flux:skeleton.line style="width: {{ rand(55, 85) }}%" /></div>
                                <div class="col-span-2 h-4"><flux:skeleton.line style="width: {{ rand(45, 80) }}%" /></div>
                                <div class="col-span-3 h-4"><flux:skeleton.line style="width: {{ rand(60, 90) }}%" /></div>
                                <div class="col-span-2 h-4 hidden md:block"><flux:skeleton.line style="width: {{ rand(45, 80) }}%" /></div>
                                <div class="col-span-2 h-4 hidden lg:block"><flux:skeleton.line style="width: {{ rand(50, 85) }}%" /></div>
                                <div class="col-span-1 flex justify-end">
                                    <div class="h-8 w-8"><flux:skeleton.line class="rounded-md" /></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <div class="h-4 w-44"><flux:skeleton.line /></div>
                        <div class="flex items-center gap-2">
                            @foreach (range(1, 4) as $i)
                                <div class="h-9 w-9"><flux:skeleton.line class="rounded-md" /></div>
                            @endforeach
                        </div>
                    </div>

                    <div class="absolute bottom-[70px] right-2 flex justify-end space-x-12 text-gray-700 dark:text-gray-200 bg-white dark:bg-zinc-800 p-2">
                        <div class="flex items-center space-x-2">
                            <span class="font-semibold">Page Total:</span>
                            <span class="inline-block w-28 h-4"><flux:skeleton.line /></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="font-semibold">Grand Total:</span>
                            <span class="inline-block w-28 h-4"><flux:skeleton.line /></span>
                        </div>
                    </div>
                </flux:skeleton.group>

            @else
                {{-- TABLE --}}
                <livewire:pages.it-leasing.it-leasing-table wire:key="it-leasing-table" />

                {{-- TOTALS --}}
                {{--
                    Page Total  : always shown, updates on every page change.
                    Grand Total : only shown on page 1 (computed by the table on page 1 only).
                                  ItLeasingIndex preserves the last known grandTotal so it
                                  doesn't get wiped when navigating to page 2+.
                --}}
                <div class="absolute bottom-[70px] right-2 flex justify-end space-x-12 text-gray-700 dark:text-gray-200 bg-white dark:bg-zinc-800 p-2">
                    <div class="flex items-center space-x-1">
                        <span class="font-semibold">Page Total:</span>
                        <span>₱ {{ number_format($pageTotal, 2) }}</span>
                    </div>

                    {{-- Grand Total: visible only on page 1 and only once grandTotal has been computed --}}
                    @if ($currentPage === 1 && $grandTotal > 0)
                        <div class="flex items-center space-x-1">
                            <span class="font-semibold">Grand Total:</span>
                            <span>₱ {{ number_format($grandTotal, 2) }}</span>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>

    {{-- DR Modal --}}
    <livewire:pages.it-leasing.generate-dr-modal />

    {{-- Delete Confirmation Modal --}}
    <flux:modal name="confirm-delete-modal" class="min-w-[22rem]">
        <div
            class="space-y-6"
            x-data="{ pendingDeleteId: null, pendingDeleteName: '' }"
            x-on:open-delete-modal.window="
                pendingDeleteId = $event.detail.id;
                pendingDeleteName = $event.detail.name;
            "
        >
            <div>
                <flux:heading size="lg">Delete Item?</flux:heading>
                <flux:subheading>
                    Are you sure you want to delete
                    <strong x-text="pendingDeleteName"></strong>?
                    This action cannot be undone.
                </flux:subheading>
            </div>

            <div class="flex gap-2 justify-end">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>

                <flux:button
                    variant="danger"
                    icon="trash"
                    x-on:click="
                        Livewire.dispatch('confirmDeleteItLeasing', { id: pendingDeleteId });
                        Flux.modal('confirm-delete-modal').close();
                    "
                >
                    Yes, Delete
                </flux:button>
            </div>
        </div>
    </flux:modal>

</div>

<script>
    window.addEventListener('open-delete-modal', function (event) {
        setTimeout(function () {
            Flux.modal('confirm-delete-modal').show();
        }, 50);
    });

    document.addEventListener('open-delivery-receipt', function (event) {
        window.open(event.detail.url, '_blank');
    });
</script>
