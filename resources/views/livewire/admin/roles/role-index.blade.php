<div class="space-y-4 w-full">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.roles.index') }}" wire:navigate>
            Roles
        </flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <x-page-heading>
        <x-slot:title>Admin Roles</x-slot:title>
        <x-slot:subtitle>Manage admin users role here</x-slot:subtitle>
    </x-page-heading>

    <div class="flex justify-end">
        @can('create users')
            <flux:button icon="plus" variant="primary" href="{{ route('admin.roles.create') }}" wire:navigate>
                Create Role
            </flux:button>
        @endcan
    </div>

    <div class="w-full border rounded-lg p-4 bg-white dark:bg-zinc-800 shadow-sm">
        <livewire:admin.roles.role-table />
    </div>
</div>
