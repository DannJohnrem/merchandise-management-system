<div class="space-y-6">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('pages.it-leasing.index') }}" wire:navigate>
            IT Leasing
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
        Edit IT Leasing Item
    </h1>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow rounded-lg p-6">

        <form wire:submit.prevent="update" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <flux:label for="category">Category *</flux:label>
                    <flux:select id="category" wire:model.defer="category">
                        <option value="Laptop">Laptop</option>
                        <option value="Printer">Printer</option>
                        <option value="Monitor">Monitor</option>
                        <option value="Desktop">Desktop</option>
                        <option value="Scanner">Scanner</option>
                        <option value="Other">Other</option>
                    </flux:select>
                </div>

                <div>
                    <flux:label for="serial_number">Serial Number *</flux:label>
                    <flux:input id="serial_number" wire:model.defer="serial_number" />
                </div>

                <div>
                    <flux:label for="brand">Brand</flux:label>
                    <flux:input id="brand" wire:model.defer="brand" />
                </div>

                <div>
                    <flux:label for="model">Model</flux:label>
                    <flux:input id="model" wire:model.defer="model" />
                </div>

                <div>
                    <flux:label for="cost">Cost</flux:label>
                    <flux:input id="cost" type="number" step="0.01" wire:model.defer="cost" />
                </div>

                <div>
                    <flux:label for="assigned_to">Assigned To</flux:label>
                    <flux:input id="assigned_to" wire:model.defer="assigned_to" />
                </div>

                <div>
                    <flux:label for="class">Class (Employee)</flux:label>
                    <flux:input id="class" wire:model.defer="class" />
                </div>

                <div>
                    <flux:label for="status">Status *</flux:label>
                    <flux:select id="status" wire:model.defer="status">
                        <option value="in_use">In Use</option>
                        <option value="returned">Returned</option>
                        <option value="repair">For Repair</option>
                    </flux:select>
                </div>

                {{-- Remarks --}}
                <div class="md:col-span-2">
                    <flux:label for="remarks">Remarks</flux:label>
                    <flux:textarea id="remarks" wire:model.defer="remarks" rows="4" />
                </div>

            </div>

            <div class="flex justify-end space-x-2">
                <flux:button variant="ghost" wire:navigate href="{{ route('pages.it-leasing.index') }}">
                    Cancel
                </flux:button>
                <flux:button variant="primary" type="submit">
                    Update
                </flux:button>
            </div>

        </form>
    </div>
</div>
