@canany(['edit classes', 'delete classes'])
    <flux:dropdown align="end">
        <flux:button icon:trailing="chevron-down" variant="outline" size="sm">
            Actions
        </flux:button>

        <flux:menu>

            {{-- Edit --}}
            @can('edit classes')
                <flux:menu.item as="a" variant="default" icon="pencil-square"
                    href="{{ route('admin.class.edit', $class) }}" wire:navigate>
                    Edit
                </flux:menu.item>
            @endcan

            <flux:menu.separator />

            {{-- Delete --}}
            @can('delete classes')
                <flux:menu.item as="button" variant="danger" icon="trash"
                    @click="$dispatch('confirm-delete-class', { id: {{ $class->id }} })">
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
