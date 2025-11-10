<flux:dropdown align="end">
    <flux:button icon:trailing="chevron-down" variant="outline" size="sm">
        Actions
    </flux:button>

    <flux:menu>
        @can('edit roles')
            <flux:menu.item as="a" variant="default" icon="pencil-square" wire:navigate
                href="{{ route('admin.roles.edit', $role) }}">
                Edit
            </flux:menu.item>
        @endcan

        <flux:menu.separator />

        @can('delete roles')
            <flux:menu.item as="button" variant="danger" icon="trash" class="cursor-pointer"
                @click="$dispatch('confirm-delete-role', { id: {{ $role->id }} })">
                Delete
            </flux:menu.item>
        @endcan
    </flux:menu>
</flux:dropdown>
