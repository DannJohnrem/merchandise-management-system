<flux:dropdown align="end">
    <flux:button icon:trailing="chevron-down" variant="outline" size="sm">
        Actions
    </flux:button>

    <flux:menu>
        {{-- @can('edit users') --}}
            <flux:menu.item as="a" wire:navigate >
                Edit
            </flux:menu.item>
        {{-- @endcan --}}

        <flux:menu.separator />

        {{-- @can('delete users') --}}
            <flux:menu.item as="button" class="cursor-pointer" variant="danger" wire:click="$emit('confirmDelete', {{ $user->id }})">
                Delete
            </flux:menu.item>
        {{-- @endcan --}}

    </flux:menu>
</flux:dropdown>
