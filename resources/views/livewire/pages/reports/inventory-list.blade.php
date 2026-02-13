<div class="space-y-4 w-full">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('reports.inventory-list') }}" wire:navigate>
            Inventory List
        </flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <x-page-heading>
        <x-slot:title>Inventory List</x-slot:title>
        <x-slot:subtitle>View and manage your inventory items here</x-slot:subtitle>
    </x-page-heading>

    <div class="relative w-full border rounded-lg p-4 bg-white dark:bg-zinc-800 shadow-sm">
        <livewire:tables.inventory-list-table />
    </div>
</div>
