@php
    // Rappasoft rows behave like objects AND arrays; array is safer
    $itemKey = data_get($row, 'item_key');

    $payload = [
        'source' => data_get($row, 'source'),
        'item_key' => $itemKey,
        'category' => data_get($row, 'category'),
        'name' => data_get($row, 'name'),
        'brand' => data_get($row, 'brand'),
        'model' => data_get($row, 'model'),
        'reorder_level' => (int) data_get($row, 'reorder_level', 0),
        'reorder_time_days' => data_get($row, 'reorder_time_days'),
        'discontinued' => (bool) data_get($row, 'discontinued', false),
    ];
@endphp

<div
    class="flex gap-2"
    x-data="{
        payload: @js($payload),
        open() {
            // open UI
            this.$dispatch('open-modal', 'reorder-setting')

            // send to the SPECIFIC component
            Livewire.dispatchTo('modals.reorder-setting-modal', 'openReorderModal', {payload: this.payload})
        }
    }"
>
    <flux:modal.trigger name="reorder-setting">
        <flux:button variant="primary" type="button" x-on:click="open()">
            Set Reorder
        </flux:button>
    </flux:modal.trigger>
</div>
