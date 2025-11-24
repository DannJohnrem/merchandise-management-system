<div class="space-y-4 w-full">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.permissions.index') }}" wire:navigate>
            Users
        </flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <x-page-heading>
        <x-slot:title>Admin Permissions</x-slot:title>
        <x-slot:subtitle>Manage admin permissions here</x-slot:subtitle>
    </x-page-heading>

    <div class="flex justify-end">
        @can('create users')
            <flux:button icon="plus" variant="primary" href="{{ route('admin.permissions.create') }}" wire:navigate>
                Create Permission
            </flux:button>
        @endcan
    </div>

    <div class="w-full border rounded-lg p-4 bg-white dark:bg-zinc-800 shadow-sm">
        <livewire:admin.permissions.permission-table />
    </div>
</div>

{{-- Delete Confirmation Listener --}}
<script>
    // Remove old listener if already registered
    document.removeEventListener('confirm-delete-permission', window.__confirmDeleteUserHandler);

    // Define global handler
    window.__confirmDeleteUserHandler = function (event) {
        const id = event.detail.id;
        if (confirm('Are you sure you want to delete this user?')) {
            Livewire.dispatch('confirmDeletePermission', { id });
        }
    };

    // Add event listener only once
    document.addEventListener('confirm-delete-permission', window.__confirmDeleteUserHandler);
</script>
