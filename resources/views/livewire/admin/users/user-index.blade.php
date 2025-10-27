<div class="space-y-4 w-full">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.users.index') }}" wire:navigate>
            Users
        </flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <x-page-heading>
        <x-slot:title>Admin Users</x-slot:title>
        <x-slot:subtitle>Manage admin users account here</x-slot:subtitle>
    </x-page-heading>

    <div class="flex justify-end">
        @can('create users')
            <flux:button icon="plus" variant="primary" href="{{ route('admin.users.create') }}" wire:navigate>
                Create User
            </flux:button>
        @endcan
    </div>

    <div class="w-full border rounded-lg p-4 bg-white dark:bg-zinc-800 shadow-sm">
        <livewire:admin.users.user-table />
    </div>
</div>
