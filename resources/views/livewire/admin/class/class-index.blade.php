<div class="space-y-4 w-full">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.class.index') }}" wire:navigate>
            Classes
        </flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <x-page-heading>
        <x-slot:title>Admin Classes</x-slot:title>
        <x-slot:subtitle>Manage class information here</x-slot:subtitle>
    </x-page-heading>

    <div class="flex justify-end">
        @can('create classes')
            <flux:button icon="plus" variant="primary" href="{{ route('admin.class.create') }}" wire:navigate>
                Create Class
            </flux:button>
        @endcan
    </div>

    <div class="w-full border rounded-lg p-4 bg-white dark:bg-zinc-800 shadow-sm">
        <livewire:admin.class.class-table />
    </div>
</div>

{{-- Delete Confirmation Listener --}}
<script>
    // Remove old listener if already registered
    document.removeEventListener('confirm-delete-class', window.__confirmDeleteClassHandler);

    // Define global handler
    window.__confirmDeleteClassHandler = function (event) {
        const id = event.detail.id;
        if (confirm('Are you sure you want to delete this class?')) {
            Livewire.dispatch('confirmDeleteClass', { id });
        }
    };

    // Add event listener only once
    document.addEventListener('confirm-delete-class', window.__confirmDeleteClassHandler);
</script>
