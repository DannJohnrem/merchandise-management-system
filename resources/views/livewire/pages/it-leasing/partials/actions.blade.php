{{-- Edit --}}
@can('edit it-leasing')
    <a href="{{ route('pages.it-leasing.edit', $item->id) }}" wire:navigate>
        <flux:button variant="outline" size="sm" icon="pencil-square">
            Edit
        </flux:button>
    </a>
@endcan

{{-- View QR (Flux Modal Trigger + Livewire Event) --}}
<flux:modal.trigger name="it-leasing-qr">
    <flux:button
        variant="outline"
        size="sm"
        icon="qr-code"
        wire:click="$dispatch('show-qr-modal', { itemId: {{ $item->id }} })"
    >
        View QR
    </flux:button>
</flux:modal.trigger>

{{-- Delete --}}
@can('delete it-leasing')
    <flux:button variant="danger" size="sm" icon="trash"
        wire:click="$dispatch('confirm-delete-it-leasing', { id: {{ $item->id }} })">
        Delete
    </flux:button>
@endcan
