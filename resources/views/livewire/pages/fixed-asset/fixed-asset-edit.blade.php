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

                <div>
                    <flux:label>Asset Tag</flux:label>
                    <flux:input wire:model.defer="asset_tag" />
                </div>

                <div>
                    <flux:label>Category *</flux:label>
                    <flux:select wire:model.defer="category">
                        <option value="">Select Category</option>
                        <option>Laptop</option>
                        <option>Desktop</option>
                        <option>Monitor</option>
                        <option>Printer</option>
                        <option>Scanner</option>
                        <option>Furniture</option>
                        <option>Equipment</option>
                        <option>Others</option>
                    </flux:select>
                </div>

                <div>
                    <flux:label>Item Name *</flux:label>
                    <flux:input wire:model.defer="asset_name" />
                </div>

                <div>
                    <flux:label>Serial Number</flux:label>
                    <flux:input wire:model.defer="serial_number" />
                </div>

                <div>
                    <flux:label>Charger Serial Number</flux:label>
                    <flux:input wire:model.defer="charger_serial_number" />
                </div>

                <div>
                    <flux:label>Brand</flux:label>
                    <flux:input wire:model.defer="brand" />
                </div>

                <div>
                    <flux:label>Model</flux:label>
                    <flux:input wire:model.defer="model" />
                </div>

                <div>
                    <flux:label>Purchase Cost</flux:label>
                    <flux:input type="number" step="0.01" wire:model.defer="purchase_cost" />
                </div>

                <div>
                    <flux:label>Supplier</flux:label>
                    <flux:input wire:model.defer="supplier" />
                </div>

                <div>
                    <flux:label>Assigned Employee</flux:label>
                    <flux:input wire:model.defer="assigned_employee" />
                </div>

                <div>
                    <flux:label>Class</flux:label>
                    <flux:select wire:model.defer="asset_class">
                        <option value="">Select Class</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->name }}">
                                {{ $class->name }} @if($class->type)({{ ucfirst($class->type) }})@endif
                            </option>
                        @endforeach
                    </flux:select>
                </div>

                <div>
                    <flux:label>Location</flux:label>
                    <flux:input wire:model.defer="location" />
                </div>

                <div>
                    <flux:label>Status</flux:label>
                    <flux:select wire:model.defer="status">
                        <option value="available">Available</option>
                        <option value="issued">Issued</option>
                        <option value="repair">Repair</option>
                        <option value="disposed">Disposed</option>
                        <option value="lost">Lost</option>
                    </flux:select>
                </div>

                <div>
                    <flux:label>Condition</flux:label>
                    <flux:select wire:model.defer="condition">
                        <option value="new">New</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                    </flux:select>
                </div>

                <div>
                    <flux:label>Purchase Date</flux:label>
                    <flux:input type="date" wire:model.defer="purchase_date" />
                </div>

                <div>
                    <flux:label>Purchase Order No.</flux:label>
                    <flux:input wire:model.defer="purchase_order_no" />
                </div>

                <div>
                    <flux:label>Warranty Expiration</flux:label>
                    <flux:input type="date" wire:model.defer="warranty_expiration" />
                </div>

                <div class="md:col-span-2">
                    <flux:label>Inclusions</flux:label>
                    <div class="space-y-2">
                        @foreach ($inclusions as $incIndex => $value)
                            <div class="flex space-x-2 mb-2">
                                <flux:input type="text"
                                    wire:model.defer="inclusions.{{ $incIndex }}"
                                    placeholder="Inclusion item" />
                                <flux:button type="button" variant="danger"
                                    wire:click.prevent="removeInclusion({{ $incIndex }})">
                                    Remove
                                </flux:button>
                            </div>
                        @endforeach
                    </div>
                    <flux:button type="button" variant="primary" wire:click.prevent="addInclusion">+ Add Inclusion</flux:button>
                </div>

                <div class="md:col-span-2">
                    <flux:label>Remarks</flux:label>
                    <flux:textarea rows="4" wire:model.defer="remarks" />
                </div>

            </div>

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
