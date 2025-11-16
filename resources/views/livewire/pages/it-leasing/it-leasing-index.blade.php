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

    <div class="w-full border rounded-lg p-4 bg-white dark:bg-zinc-800 shadow-sm">
        <livewire:pages.it-leasing.it-leasing-table />
    </div>

    {{-- QR Code Modal --}}
    <livewire:pages.it-leasing.it-leasing-qr-modal />
</div>

{{-- Delete Confirmation Listener --}}
<script>
    document.removeEventListener('confirm-delete-it-leasing', window.__confirmDeleteItLeasingHandler);

    window.__confirmDeleteItLeasingHandler = function (event) {
        const id = event.detail.id;
        if (confirm('Are you sure you want to delete this IT Leasing record?')) {
            Livewire.dispatch('confirmDeleteItLeasing', { id });
        }
    };

    document.addEventListener('confirm-delete-it-leasing', window.__confirmDeleteItLeasingHandler);
</script>
