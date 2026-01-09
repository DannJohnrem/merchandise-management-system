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
            <flux:button icon="plus" variant="primary" href="{{ route('it-leasing.create') }}" wire:navigate>
                Create IT Leasing
            </flux:button>
        @endcan
    </div>

    <div class="relative w-full border rounded-lg p-4 bg-white dark:bg-zinc-800 shadow-sm">
        <livewire:pages.it-leasing.it-leasing-table wire:key="it-leasing-table" />

        <div
            class="absolute bottom-[70px] right-2 flex justify-end space-x-12 text-gray-700 dark:text-gray-200 bg-white dark:bg-zinc-800 p-2">
            <!-- Page Total -->
            <div class="flex items-center space-x-1">
                <span class="font-semibold">Page Total:</span>
                <span>₱ {{ number_format($pageTotal, 2) }}</span>
            </div>

            <!-- Grand Total (show only on page 1) -->
            @if ($currentPage === 1 && $grandTotal > 0)
                <div class="flex items-center space-x-1">
                    <span class="font-semibold">Grand Total:</span>
                    <span>₱ {{ number_format($grandTotal, 2) }}</span>
                </div>
            @endif
        </div>

    </div>

    {{-- QR Code Modal --}}
    {{-- <livewire:pages.it-leasing.it-leasing-qr-modal /> --}}
</div>

{{-- Delete Confirmation Listener --}}
<script>
    document.removeEventListener('confirm-delete-it-leasing', window.__confirmDeleteItLeasingHandler);

    window.__confirmDeleteItLeasingHandler = function(event) {
        const id = event.detail.id;
        if (confirm('Are you sure you want to delete this IT Leasing record?')) {
            Livewire.dispatch('confirmDeleteItLeasing', {
                id
            });
        }
    };

    document.addEventListener('confirm-delete-it-leasing', window.__confirmDeleteItLeasingHandler);
</script>
