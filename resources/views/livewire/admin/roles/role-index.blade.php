<div class="space-y-4 w-full">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.roles.index') }}" wire:navigate>
            Roles
        </flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Page Heading --}}
    <x-page-heading>
        <x-slot:title>Admin Roles</x-slot:title>
        <x-slot:subtitle>Manage admin users roles here</x-slot:subtitle>
    </x-page-heading>

    {{-- Create Role Button --}}
    <div class="flex justify-end">
        @can('create roles')
            <flux:button icon="plus" variant="primary" href="{{ route('admin.roles.create') }}" wire:navigate>
                Create Role
            </flux:button>
        @endcan
    </div>

    {{-- Role Table --}}
    <div class="w-full border rounded-lg p-4 bg-white dark:bg-zinc-800 shadow-sm">
        <livewire:admin.roles.role-table />
    </div>
</div>

{{-- Delete Confirmation Listener --}}
<script>
    document.addEventListener('confirm-delete-role', (event) => {
        const id = event.detail.id;
        if (confirm('Are you sure you want to delete this role?')) {
            Livewire.dispatch('confirmDeleteRole', { id });
        }
    });
</script>
