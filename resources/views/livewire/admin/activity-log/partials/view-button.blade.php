<flux:modal.trigger name="activity-log-details">
    <flux:button variant="primary" size="sm" icon="eye"
        wire:click="$emit('show-activity-log', { id: {{ $row->id }} })">
        View
    </flux:button>
</flux:modal.trigger>
