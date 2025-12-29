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
                    <flux:label>Category *</flux:label>
                    <flux:select wire:model.defer="category">
                        <option value="">Select Category</option>
                        <option value="Laptop">Laptop</option>
                        <option value="Desktop">Desktop</option>
                        <option value="Monitor">Monitor</option>
                        <option value="Printer">Printer</option>
                        <option value="Scanner">Scanner</option>
                        <option value="Other">Other</option>
                    </flux:select>
                </div>

                {{-- Item Name --}}
                <div>
                    <flux:label>Item Name *</flux:label>
                    <flux:input wire:model.defer="item_name" placeholder="Item name"/>
                </div>

                {{-- Serial Number --}}
                <div>
                    <flux:label>Serial Number</flux:label>
                    <flux:input wire:model.defer="serial_number"/>
                </div>

                {{-- Charger Serial Number --}}
                <div>
                    <flux:label>Charger Serial Number</flux:label>
                    <flux:input wire:model.defer="charger_serial_number"/>
                </div>

                {{-- Brand --}}
                <div>
                    <flux:label>Brand</flux:label>
                    <flux:input wire:model.defer="brand"/>
                </div>

                {{-- Model --}}
                <div>
                    <flux:label>Model</flux:label>
                    <flux:input wire:model.defer="model"/>
                </div>

                {{-- Purchase Cost --}}
                <div>
                    <flux:label>Cost</flux:label>
                    <flux:input type="number" step="0.01" wire:model.defer="purchase_cost"/>
                </div>

                {{-- Supplier --}}
                <div>
                    <flux:label>Supplier</flux:label>
                    <flux:input wire:model.defer="supplier"/>
                </div>

                {{-- Purchase Order No --}}
                <div>
                    <flux:label>Purchase Order No.</flux:label>
                    <flux:input wire:model.defer="purchase_order_no"/>
                </div>

                {{-- Purchase Date --}}
                <div>
                    <flux:label>Purchase Date</flux:label>
                    <flux:input type="date" wire:model.defer="purchase_date"/>
                </div>

                {{-- Warranty Expiration --}}
                <div>
                    <flux:label>Warranty Expiration</flux:label>
                    <flux:input type="date" wire:model.defer="warranty_expiration"/>
                </div>

                {{-- Assigned Company --}}
                <div>
                    <flux:label>Assigned Company *</flux:label>
                    <flux:input wire:model.defer="assigned_company"/>
                </div>

                {{-- Assigned Employee --}}
                <div>
                    <flux:label>Assigned Employee</flux:label>
                    <flux:input wire:model.defer="assigned_employee"/>
                </div>

                {{-- Location --}}
                <div>
                    <flux:label>Location</flux:label>
                    <flux:input wire:model.defer="location"/>
                </div>

                {{-- Status --}}
                <div>
                    <flux:label>Status *</flux:label>
                    <flux:select wire:model.defer="status">
                        <option value="available">Available</option>
                        <option value="deployed">Deployed</option>
                        <option value="in_repair">In Repair</option>
                        <option value="returned">Returned</option>
                        <option value="lost">Lost</option>
                    </flux:select>
                </div>

                {{-- Condition --}}
                <div>
                    <flux:label>Condition</flux:label>
                    <flux:select wire:model.defer="condition">
                        <option value="">Select Condition</option>
                        <option value="new">New</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                    </flux:select>
                </div>

                {{-- Inclusions --}}
                <div class="md:col-span-2">
                    <flux:label>Inclusions</flux:label>

                    <div class="space-y-2">
                        @foreach ($inclusions as $index => $value)
                            <div class="flex space-x-2 mb-2">
                                <flux:input type="text" wire:model.defer="inclusions.{{ $index }}" placeholder="Inclusion item"/>
                                <flux:button type="button" variant="danger" wire:click.prevent="removeInclusion({{ $index }})">Remove</flux:button>
                            </div>
                        @endforeach
                    </div>

                    <flux:button type="button" variant="primary" wire:click.prevent="addInclusion()">+ Add Inclusion</flux:button>
                </div>

                {{-- Remarks --}}
                <div class="md:col-span-2">
                    <flux:label>Remarks</flux:label>
                    <flux:textarea rows="4" wire:model.defer="remarks"/>
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
