{{-- View --}}
@can('view it-leasing')
    <a href="{{ route('it-leasing.show', $item->id) }}" wire:navigate>
        <flux:button variant="primary" color="sky" size="sm" icon="eye">
            View
        </flux:button>
    </a>
@endcan

{{-- Edit --}}
@can('edit it-leasing')
    <flux:button
        variant="primary"
        color="amber"
        size="sm"
        icon="pencil-square"
        onclick="
            var page = new URLSearchParams(window.location.search).get('it-leasing-tablePage') || 1;
            Livewire.navigate('{{ route('it-leasing.edit', ['item' => $item->id]) }}?page=' + page);
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
