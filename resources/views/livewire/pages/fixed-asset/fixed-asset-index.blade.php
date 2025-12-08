<div class="space-y-4 w-full">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('fixed-asset.index') }}" wire:navigate>
            Fixed Assets
        </flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <x-page-heading>
        <x-slot:title>Fixed Asset Management</x-slot:title>
        <x-slot:subtitle>Manage fixed asset information here</x-slot:subtitle>
    </x-page-heading>

    <div class="flex justify-end">
        <flux:button icon="plus" variant="primary" href="{{ route('fixed-asset.create') }}" wire:navigate>
            Create Fixed Asset
        </flux:button>
    </div>

    <div class="w-full border rounded-lg p-4 bg-white dark:bg-zinc-800 shadow-sm">
        <livewire:pages.fixed-asset.fixed-asset-table />
    </div>

    <livewire:pages.fixed-asset.fixed-asset-qr-modal />
</div>

<script>
    // Remove old listener if already registered
    document.removeEventListener('confirm-delete-fixed-asset', window.__confirmDeleteFixedAssetHandler);

    // Define global handler
    window.__confirmDeleteFixedAssetHandler = function (event) {
        const id = event.detail.id;
        if (confirm('Are you sure you want to delete this fixed asset?')) {
            Livewire.dispatch('confirmDeleteFixedAsset', { id });
        }
    };

    // Add event listener only once
    document.addEventListener('confirm-delete-fixed-asset', window.__confirmDeleteFixedAssetHandler);
</script>
