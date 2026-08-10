{{-- View --}}
@can('view it-leasing')
    <flux:button
        variant="primary"
        color="sky"
        size="sm"
        icon="eye"
        onclick="
            var qs = window.location.search;
            Livewire.navigate('{{ route('it-leasing.show', ['itLeasing' => $item->id]) }}?redirect=' + encodeURIComponent(window.location.pathname + qs));
        "
    >
        View
    </flux:button>
@endcan

{{-- Edit --}}
@can('edit it-leasing')
    <flux:button
        variant="primary"
        color="amber"
        size="sm"
        icon="pencil-square"
        onclick="
            var qs = window.location.search;
            Livewire.navigate('{{ route('it-leasing.edit', ['item' => $item->id]) }}?redirect=' + encodeURIComponent(window.location.pathname + qs));
        "
    >
        Edit
    </flux:button>
@endcan

{{-- Delete --}}
@can('delete it-leasing')
    <flux:button
        variant="danger"
        size="sm"
        icon="trash"
        onclick="window.dispatchEvent(new CustomEvent('open-delete-modal', { detail: { id: {{ $item->id }}, name: '{{ addslashes($item->serial_number ?? 'this item') }}' } }))"
    >
        Delete
    </flux:button>
@endcan
