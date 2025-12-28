<div class="space-y-6">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('it-leasing.index') }}" wire:navigate>
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

                {{-- Category --}}
                <div>
                    <flux:label for="category">Category *</flux:label>
                    <flux:select id="category" wire:model.defer="category">
                        <option value="">Select Category</option>
                        <option value="Laptop">Laptop</option>
                        <option value="Printer">Printer</option>
                        <option value="Monitor">Monitor</option>
                        <option value="Desktop">Desktop</option>
                        <option value="Scanner">Scanner</option>
                        <option value="Other">Other</option>
                    </flux:select>
                </div>

                {{-- Serial Number --}}
                <div>
                    <flux:label for="serial_number">Serial Number *</flux:label>
                    <flux:input id="serial_number" wire:model.defer="serial_number" placeholder="Enter serial number"/>
                </div>

                {{-- Brand --}}
                <div>
                    <flux:label for="brand">Brand</flux:label>
                    <flux:input id="brand" wire:model.defer="brand" placeholder="HP, Lenovo, etc."/>
                </div>

                {{-- Model --}}
                <div>
                    <flux:label for="model">Model</flux:label>
                    <flux:input id="model" wire:model.defer="model" placeholder="Model name"/>
                </div>

                {{-- Purchase Cost --}}
                <div>
                    <flux:label for="purchase_cost">Cost</flux:label>
                    <flux:input id="purchase_cost" type="number" step="0.01" wire:model.defer="purchase_cost" placeholder="0.00"/>
                </div>

                {{-- Assigned To --}}
                <div>
                    <flux:label for="assigned_to">Assigned To</flux:label>
                    <flux:input id="assigned_to" wire:model.defer="assigned_company" placeholder="Department or company"/>
                </div>

                {{-- Class / Employee --}}
                <div>
                    <flux:label for="class">Class (Employee)</flux:label>
                    <flux:input id="class" wire:model.defer="assigned_employee" placeholder="Employee name"/>
                </div>

                {{-- Status --}}
                <div>
                    <flux:label for="status">Status *</flux:label>
                    <flux:select id="status" wire:model.defer="status">
                        <option value="available">Available</option>
                        <option value="deployed">Deployed</option>
                        <option value="in_repair">In Repair</option>
                        <option value="returned">Returned</option>
                        <option value="lost">Lost</option>
                    </flux:select>
                </div>

                {{-- Condition --}}
                <div>
                    <flux:label for="condition">Condition</flux:label>
                    <flux:select id="condition" wire:model.defer="condition">
                        <option value="">Select Condition</option>
                        <option value="new">New</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                    </flux:select>
                </div>

                {{-- Remarks --}}
                <div class="md:col-span-2">
                    <flux:label for="remarks">Remarks</flux:label>
                    <flux:textarea id="remarks" wire:model.defer="remarks" rows="4" placeholder="Additional notes..."/>
                </div>

            </div>

            {{-- Actions --}}
            <div class="flex justify-end space-x-2">
                <flux:button variant="ghost" wire:navigate href="{{ route('it-leasing.index') }}">
                    Cancel
                </flux:button>
                <flux:button variant="primary" type="submit">
                    Update
                </flux:button>
            </div>

        </form>
    </div>
</div>
