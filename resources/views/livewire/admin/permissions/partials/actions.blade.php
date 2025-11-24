@canany(['edit permissions', 'delete permissions'])
    <flux:dropdown align="end">
        <flux:button icon:trailing="chevron-down" variant="outline" size="sm">
            Actions
        </flux:button>

        <flux:menu>
            @can('edit permissions')
                <flux:menu.item as="a" variant="default" icon="pencil-square" wire:navigate
                    href="{{ route('admin.permissions.edit', $permission) }}">
                    Edit
                </flux:menu.item>
            @endcan

            @can('delete permissions')
                <flux:menu.separator />
                <flux:menu.item as="button" variant="danger" icon="trash" class="cursor-pointer"
                    @click="$dispatch('confirm-delete-permission', { id: {{ $permission->id }} })">
                    Delete
                </flux:menu.item>
            @endcan
        </flux:menu>
    </flux:dropdown>
@else
    <flux:button variant="ghost" size="sm" disabled>
        No Actions
    </flux:button>
@endcanany
