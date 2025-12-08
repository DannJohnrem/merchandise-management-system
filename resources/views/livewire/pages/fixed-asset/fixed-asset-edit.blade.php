<div class="space-y-6">

    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('fixed-asset.index') }}" wire:navigate>
            Fixed Assets
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
        Edit Fixed Asset
    </h1>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow rounded-lg p-6">

        <form wire:submit.prevent="update" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Asset Tag --}}
                <div>
                    <flux:label for="asset_tag">Asset Tag <span class="text-red-400">*</span></flux:label>
                    <flux:input id="asset_tag" wire:model.defer="asset_tag" />
                </div>

                {{-- Category --}}
                <div>
                    <flux:label for="category">Category <span class="text-red-400">*</span></flux:label>
                    <flux:select id="category" wire:model.defer="category">
                        <option value="">Select Category</option>
                        <option value="Laptop">Laptop</option>
                        <option value="Desktop">Desktop</option>
                        <option value="Monitor">Monitor</option>
                        <option value="Printer">Printer</option>
                        <option value="Scanner">Scanner</option>
                        <option value="Furniture">Furniture</option>
                        <option value="Equipment">Equipment</option>
                        <option value="Others">Others</option>
                    </flux:select>
                </div>

                {{-- Asset Name --}}
                <div>
                    <flux:label for="asset_name">Item Name <span class="text-red-400">*</span></flux:label>
                    <flux:input id="asset_name" wire:model.defer="asset_name" />
                </div>

                {{-- Serial Number --}}
                <div>
                    <flux:label for="serial_number">Serial Number</flux:label>
                    <flux:input id="serial_number" wire:model.defer="serial_number" />
                </div>

                {{-- Brand --}}
                <div>
                    <flux:label for="brand">Brand</flux:label>
                    <flux:input id="brand" wire:model.defer="brand" />
                </div>

                {{-- Model --}}
                <div>
                    <flux:label for="model">Model</flux:label>
                    <flux:input id="model" wire:model.defer="model" />
                </div>

                {{-- Purchase Cost --}}
                <div>
                    <flux:label for="cost">Purchase Cost</flux:label>
                    <flux:input id="cost" type="number" step="0.01" wire:model.defer="cost" />
                </div>

                {{-- Supplier --}}
                <div>
                    <flux:label for="supplier">Supplier</flux:label>
                    <flux:input id="supplier" wire:model.defer="supplier" />
                </div>

                {{-- Assigned To --}}
                <div>
                    <flux:label for="assigned_to">Assigned To</flux:label>
                    <flux:input id="assigned_to" wire:model.defer="assigned_to" />
                </div>

                {{-- Class --}}
                <div>
                    <flux:label for="class">Class (Employee)</flux:label>
                    <flux:input id="class" wire:model.defer="class" />
                </div>

                {{-- Location --}}
                <div>
                    <flux:label for="location">Location</flux:label>
                    <flux:input id="location" wire:model.defer="location" />
                </div>

                {{-- Status --}}
                <div>
                    <flux:label for="status">Status</flux:label>
                    <flux:select id="status" wire:model.defer="status">
                        <option value="">Select Status</option>
                        <option value="active">Active</option>
                        <option value="in_use">In Use</option>
                        <option value="repair">For Repair</option>
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

                {{-- Purchase Date --}}
                <div>
                    <flux:label for="purchase_date">Purchase Date</flux:label>
                    <flux:input id="purchase_date" type="date" wire:model.defer="purchase_date" />
                </div>

                {{-- PO Number --}}
                <div>
                    <flux:label for="purchase_order_no">Purchase Order No.</flux:label>
                    <flux:input id="purchase_order_no" wire:model.defer="purchase_order_no" />
                </div>

                {{-- Warranty --}}
                <div>
                    <flux:label for="warranty_expiration">Warranty Expiration</flux:label>
                    <flux:input id="warranty_expiration" type="date" wire:model.defer="warranty_expiration" />
                </div>

                {{-- Remarks --}}
                <div class="md:col-span-2">
                    <flux:label for="remarks">Remarks</flux:label>
                    <flux:textarea id="remarks" wire:model.defer="remarks" rows="4" />
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end space-x-2">
                <flux:button variant="ghost" wire:navigate href="{{ route('fixed-asset.index') }}">
                    Cancel
                </flux:button>

                <flux:button variant="primary" type="submit">
                    Update
                </flux:button>
            </div>

        </form>
    </div>

</div>
