<div class="space-y-6">

    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('it-leasing.index') }}" wire:navigate>
            IT Leasing
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Create</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
        Add New IT Leasing Items
    </h1>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow rounded-lg p-6">
        <form wire:submit.prevent="save" class="space-y-6">

            @foreach ($items as $index => $item)
                <div class="border rounded-lg p-4 bg-gray-50 dark:bg-gray-800 space-y-4">

                    {{-- Header --}}
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold">
                            Item {{ $index + 1 }}
                        </h3>

                        @if (count($items) > 1)
                            <flux:button type="button" variant="danger" size="sm"
                                wire:click="removeItem({{ $index }})">
                                Remove
                            </flux:button>
                        @endif
                    </div>

                    {{-- Fields --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Category --}}
                        <div>
                            <flux:label>Category <span class="text-red-400 ml-2">*</span></flux:label>
                            <flux:select wire:model.defer="items.{{ $index }}.category">
                                <option value="">Select Category</option>
                                <option>Laptop</option>
                                <option>Desktop</option>
                                <option>Monitor</option>
                                <option>Printer</option>
                                <option>Scanner</option>
                                <option>Other</option>
                            </flux:select>
                        </div>

                        {{-- Item Name --}}
                        <div>
                            <flux:label>Item Name <span class="text-red-400 ml-2">*</span></flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.item_name"/>
                        </div>

                        {{-- Serial Number --}}
                        <div>
                            <flux:label>Serial Number <span class="text-red-400 ml-2">*</span></flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.serial_number"/>
                        </div>

                        {{-- Charger Serial Number --}}
                        <div>
                            <flux:label>Charger Serial Number</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.charger_serial_number"/>
                        </div>

                        {{-- Brand --}}
                        <div>
                            <flux:label>Brand</flux:label>
                            <flux:input wire:model.live="items.{{ $index }}.brand"/>
                        </div>

                        {{-- Model --}}
                        <div>
                            <flux:label>Model</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.model"/>
                        </div>

                        {{-- Purchase Cost --}}
                        <div>
                            <flux:label>Purchase Cost</flux:label>
                            <flux:input type="number" step="0.01"
                                wire:model.defer="items.{{ $index }}.purchase_cost"/>
                        </div>

                        {{-- Rental Rate Per Month --}}
                        <div>
                            <flux:label>Rental Rate Per Month</flux:label>
                            <flux:input type="number" step="0.01"
                                wire:model.defer="items.{{ $index }}.rental_rate_per_month"/>
                        </div>

                        {{-- Supplier --}}
                        <div>
                            <flux:label>Supplier</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.supplier"/>
                        </div>

                        {{-- Purchase Order No --}}
                        <div>
                            <flux:label>Purchase Order No</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.purchase_order_no"/>
                        </div>

                        {{-- Purchase Date --}}
                        <div>
                            <flux:label>Purchase Date</flux:label>
                            <flux:input type="date"
                                wire:model.defer="items.{{ $index }}.purchase_date"/>
                        </div>

                        {{-- Warranty Expiration --}}
                        <div>
                            <flux:label>Warranty Expiration</flux:label>
                            <flux:input type="date"
                                wire:model.defer="items.{{ $index }}.warranty_expiration"/>
                        </div>

                        {{-- Assigned Company --}}
                        <div>
                            <flux:label>Assigned Company <span class="text-red-400 ml-2">*</span></flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.assigned_company"/>
                        </div>

                        {{-- Assigned Employee --}}
                        <div>
                            <flux:label>Assigned Employee</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.assigned_employee"/>
                        </div>

                        {{-- Location --}}
                        <div>
                            <flux:label>Location</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.location"/>
                        </div>

                        {{-- Status --}}
                        <div>
                            <flux:label>Status <span class="text-red-400 ml-2">*</span></flux:label>
                            <flux:select wire:model.defer="items.{{ $index }}.status">
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
                            <flux:select wire:model.defer="items.{{ $index }}.condition">
                                <option value="new">New</option>
                                <option value="good">Good</option>
                                <option value="fair">Fair</option>
                                <option value="poor">Poor</option>
                            </flux:select>
                        </div>

                        {{-- Inclusions --}}
                        <div class="md:col-span-2">
                            <flux:label>Inclusions</flux:label>

                            @foreach ($item['inclusions'] as $incIndex => $value)
                                <div class="flex gap-2 mb-2">
                                    <flux:input
                                        wire:model.defer="items.{{ $index }}.inclusions.{{ $incIndex }}"
                                        placeholder="Inclusion item"/>
                                    <flux:button type="button" variant="danger"
                                        wire:click.prevent="removeInclusion({{ $index }}, {{ $incIndex }})">
                                        Remove
                                    </flux:button>
                                </div>
                            @endforeach

                            <flux:button type="button" variant="primary"
                                wire:click.prevent="addInclusion({{ $index }})">
                                + Add Inclusion
                            </flux:button>
                        </div>

                        {{-- Remarks --}}
                        <div class="md:col-span-2">
                            <flux:label>Remarks</flux:label>
                            <flux:textarea rows="4"
                                wire:model.defer="items.{{ $index }}.remarks"/>
                        </div>

                    </div>
                </div>
            @endforeach

            {{-- Actions --}}
            <div class="flex justify-between items-center">
                <flux:button type="button" variant="outline" wire:click="addItem">
                    + Add Another Item
                </flux:button>

                <div class="flex space-x-2">
                    <flux:button variant="ghost" wire:navigate href="{{ route('it-leasing.index') }}">
                        Cancel
                    </flux:button>

                    <flux:button variant="primary" type="submit">
                        {{ count($items) > 1 ? 'Save All' : 'Save' }}
                    </flux:button>
                </div>
            </div>

        </form>
    </div>
</div>
