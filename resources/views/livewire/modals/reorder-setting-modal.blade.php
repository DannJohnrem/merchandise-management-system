<flux:modal name="reorder-setting" class="md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Reorder Settings</flux:heading>

            <flux:text class="mt-2">
                <span class="font-medium">{{ $source }}</span>
                • {{ $category }} • {{ $name }} • {{ $brand }} • {{ $model }}
            </flux:text>

            @if ($errors->any())
                <div class="mt-2 text-sm text-red-500">
                    {{ implode(', ', $errors->all()) }}
                </div>
            @endif
        </div>

        <flux:input label="Reorder level" type="number" min="0" wire:model="reorder_level" />
        <flux:input label="Reorder time (days)" type="number" min="0" wire:model="reorder_time_days" />

        <div class="flex items-center gap-2">
            <flux:checkbox wire:model="discontinued" />
            <flux:text>Discontinued</flux:text>
        </div>

        <div class="flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="filled" type="button">Cancel</flux:button>
            </flux:modal.close>

            <flux:button type="button" variant="primary" wire:click="save">
                Save changes
            </flux:button>
        </div>
    </div>
</flux:modal>