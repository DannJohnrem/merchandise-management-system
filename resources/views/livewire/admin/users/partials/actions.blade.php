@canany(['edit users', 'delete users'])
    <flux:dropdown align="end">
        <flux:button icon:trailing="chevron-down" variant="outline" size="sm">
            Actions
        </flux:button>

        <flux:menu>
            @can('edit users')
                <flux:menu.item as="a" variant="default" icon="pencil-square" wire:navigate
                    href="{{ route('admin.users.edit', $user) }}">
                    Edit
                </flux:menu.item>
            @endcan

            <flux:menu.separator />

            @can('delete users')
                <flux:menu.item as="button" variant="danger" icon="trash" class="cursor-pointer"
                    @click="$dispatch('confirm-delete-user', { id: {{ $user->id }} })">
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
