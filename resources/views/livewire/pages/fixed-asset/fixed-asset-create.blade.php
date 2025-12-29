<div class="space-y-6">

    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('fixed-asset.index') }}" wire:navigate>
            Fixed Assets
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Create</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
        Add Fixed Assets
    </h1>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow rounded-lg p-6">

        <form wire:submit.prevent="save" class="space-y-6">

            @foreach ($items as $index => $item)
                <div class="border rounded-lg p-4 bg-gray-50 dark:bg-gray-800 space-y-4">

                    {{-- Header --}}
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Item {{ $index + 1 }}</h3>
                        @if (count($items) > 1)
                            <flux:button type="button" variant="danger" size="sm"
                                wire:click="removeItem({{ $index }})">
                                Remove
                            </flux:button>
                        @endif
                    </div>

                    {{-- Fields --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <flux:label>Asset Tag</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.asset_tag"
                                placeholder="Asset tag code" />
                        </div>

                        <div>
                            <flux:label>Category <span class="text-red-400">*</span></flux:label>
                            <flux:select wire:model.defer="items.{{ $index }}.category">
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

                        <div>
                            <flux:label>Item Name <span class="text-red-400">*</span></flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.asset_name"
                                placeholder="Item name / asset name" />
                        </div>

                        <div>
                            <flux:label>Serial Number</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.serial_number" />
                        </div>

                        <div>
                            <flux:label>Charger Serial Number</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.charger_serial_number" />
                        </div>

                        <div>
                            <flux:label>Brand</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.brand" />
                        </div>

                        <div>
                            <flux:label>Model</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.model" />
                        </div>

                        <div>
                            <flux:label>Purchase Cost</flux:label>
                            <flux:input type="number" step="0.01"
                                wire:model.defer="items.{{ $index }}.purchase_cost" />
                        </div>

                        <div>
                            <flux:label>Supplier</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.supplier" />
                        </div>

                        <div>
                            <flux:label>Assigned To</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.assigned_employee" />
                        </div>

                        <div>
                            <flux:label>Class</flux:label>
                            <flux:select wire:model.defer="items.{{ $index }}.asset_class">
                                <option value="">Select Class</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->name }}">
                                        {{ $class->name }} @if ($class->type)
                                            ({{ ucfirst($class->type) }})
                                        @endif
                                    </option>
                                @endforeach
                            </flux:select>
                        </div>

                        <div>
                            <flux:label>Location</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.location" />
                        </div>

                        <div>
                            <flux:label>Status</flux:label>
                            <flux:select wire:model.defer="items.{{ $index }}.status">
                                <option value="available">Available</option>
                                <option value="issued">Issued</option>
                                <option value="repair">For Repair</option>
                                <option value="disposed">Disposed</option>
                                <option value="lost">Lost</option>
                            </flux:select>
                        </div>

                        <div>
                            <flux:label>Condition</flux:label>
                            <flux:select wire:model.defer="items.{{ $index }}.condition">
                                <option value="new">New</option>
                                <option value="good">Good</option>
                                <option value="fair">Fair</option>
                                <option value="poor">Poor</option>
                            </flux:select>
                        </div>

                        <div>
                            <flux:label>Purchase Date</flux:label>
                            <flux:input type="date" wire:model.defer="items.{{ $index }}.purchase_date" />
                        </div>

                        <div>
                            <flux:label>Purchase Order No.</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.purchase_order_no" />
                        </div>

                        <div>
                            <flux:label>Warranty Expiration</flux:label>
                            <flux:input type="date"
                                wire:model.defer="items.{{ $index }}.warranty_expiration" />
                        </div>

                        <div class="md:col-span-2">
                            <flux:label>Inclusions</flux:label>
                            <div class="space-y-2">
                                @foreach ($item['inclusions'] ?? $inclusions as $incIndex => $value)
                                    <div class="flex space-x-2 mb-2">
                                        <flux:input type="text"
                                            wire:model.defer="items.{{ $index }}.inclusions.{{ $incIndex }}"
                                            placeholder="Inclusion item" />
                                        <flux:button type="button" variant="danger"
                                            wire:click.prevent="removeInclusion({{ $index }}, {{ $incIndex }})">
                                            Remove</flux:button>
                                    </div>
                                @endforeach
                            </div>
                            <flux:button type="button" variant="primary"
                                wire:click.prevent="addInclusion({{ $index }})">+ Add Inclusion</flux:button>
                        </div>

                        <div class="md:col-span-2">
                            <flux:label>Remarks</flux:label>
                            <flux:textarea rows="4" wire:model.defer="items.{{ $index }}.remarks" />
                        </div>

                    </div>
                </div>
            @endforeach

            {{-- Actions --}}
            <div class="flex justify-between items-center">
                <flux:button type="button" variant="outline" wire:click="addItem">+ Add Another Item</flux:button>
                <div class="flex space-x-2">
                    <flux:button variant="ghost" wire:navigate href="{{ route('fixed-asset.index') }}">Cancel
                    </flux:button>
                    <flux:button variant="primary" type="submit">{{ count($items) > 1 ? 'Save All' : 'Save' }}
                    </flux:button>
                </div>
            </div>

        </form>
    </div>
</div>
