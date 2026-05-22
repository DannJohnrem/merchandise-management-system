{{-- resources/views/livewire/pages/fixed-asset/fixed-asset-create.blade.php --}}

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

            {{-- GLOBAL ERROR SUMMARY (optional but helpful) --}}
            @if ($errors->any())
                <div
                    class="rounded-lg border border-red-300 bg-red-50 p-4 text-red-700 dark:border-red-700/50 dark:bg-red-900/20 dark:text-red-200">
                    <div class="font-semibold mb-2">Please fix the errors below:</div>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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

                        {{-- Asset Tag --}}
                        <div>
                            <flux:label>Asset Tag</flux:label>
                            <flux:input wire:model="items.{{ $index }}.asset_tag" readonly
                                placeholder="Auto-generated" />
                            <p class="mt-1 text-xs text-gray-500">
                                Auto-generated based on category. Final tag is set on save.
                            </p>
                        </div>

                        {{-- Category --}}
                        <div>
                            <flux:label>Category <span class="text-red-400">*</span></flux:label>

                            <flux:select wire:model.defer="items.{{ $index }}.category"
                                wire:change="generatePreviewTag({{ $index }})">
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

                            @error("items.$index.category")
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>


                        {{-- Item Name --}}
                        <div>
                            <flux:label>Item Name <span class="text-red-400">*</span></flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.asset_name"
                                placeholder="Item name / asset name" />
                            @error("items.$index.asset_name")
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Serial Number --}}
                        <div>
                            <flux:label>Serial Number</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.serial_number" />
                            @error("items.$index.serial_number")
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Charger Serial Number --}}
                        <div>
                            <flux:label>Charger Serial Number</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.charger_serial_number" />
                            @error("items.$index.charger_serial_number")
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Brand --}}
                        <div>
                            <flux:label>Brand</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.brand" />
                            @error("items.$index.brand")
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Model --}}
                        <div>
                            <flux:label>Model</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.model" />
                            @error("items.$index.model")
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Purchase Cost --}}
                        <div>
                            <flux:label>Purchase Cost</flux:label>
                            <flux:input type="number" step="0.01"
                                wire:model.defer="items.{{ $index }}.purchase_cost" />
                            @error("items.$index.purchase_cost")
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Supplier --}}
                        <div>
                            <flux:label>Supplier</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.supplier" />
                            @error("items.$index.supplier")
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Assigned Employee --}}
                        <div>
                            <flux:label>Assigned Employee</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.assigned_employee" />
                            @error("items.$index.assigned_employee")
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Class --}}
                        <div>
                            <flux:label>Class</flux:label>
                            <flux:select wire:model.defer="items.{{ $index }}.asset_class">
                                <option value="">Select Class</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->name }}">{{ $class->name }}</option>
                                @endforeach
                            </flux:select>
                            @error("items.$index.asset_class")
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Location --}}
                        <div>
                            <flux:label>Location</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.location" />
                            @error("items.$index.location")
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <flux:label>Status</flux:label>
                            <flux:select wire:model.defer="items.{{ $index }}.status">
                                <option value="available">Available</option>
                                <option value="issued">Issued</option>
                                <option value="repair">For Repair</option>
                                <option value="disposed">Disposed</option>
                                <option value="lost">Lost</option>
                            </flux:select>
                            @error("items.$index.status")
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
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
                            @error("items.$index.condition")
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Purchase Date --}}
                        <div>
                            <flux:label>Purchase Date</flux:label>
                            <flux:input type="date" wire:model.defer="items.{{ $index }}.purchase_date" />
                            @error("items.$index.purchase_date")
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Purchase Order No --}}
                        <div>
                            <flux:label>Purchase Order No.</flux:label>
                            <flux:input wire:model.defer="items.{{ $index }}.purchase_order_no" />
                            @error("items.$index.purchase_order_no")
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Warranty Expiration --}}
                        <div>
                            <flux:label>Warranty Expiration</flux:label>
                            <flux:input type="date"
                                wire:model.defer="items.{{ $index }}.warranty_expiration" />
                            @error("items.$index.warranty_expiration")
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Inclusions --}}
                        <div class="md:col-span-2">
                            <flux:label>Inclusions</flux:label>

                            <div class="space-y-2">
                                @foreach ($item['inclusions'] ?? [] as $incIndex => $value)
                                    <div class="flex space-x-2">
                                        <flux:input type="text"
                                            wire:model.defer="items.{{ $index }}.inclusions.{{ $incIndex }}"
                                            placeholder="Inclusion item" />
                                        <flux:button type="button" variant="danger"
                                            wire:click.prevent="removeInclusion({{ $index }}, {{ $incIndex }})">
                                            Remove
                                        </flux:button>
                                    </div>

                                    @error("items.$index.inclusions.$incIndex")
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                @endforeach
                            </div>

                            @error("items.$index.inclusions")
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror

                            <flux:button type="button" variant="primary"
                                wire:click.prevent="addInclusion({{ $index }})" class="mt-2">
                                + Add Inclusion
                            </flux:button>
                        </div>

                        {{-- Remarks --}}
                        <div class="md:col-span-2">
                            <flux:label>Remarks</flux:label>
                            <flux:textarea rows="4" wire:model.defer="items.{{ $index }}.remarks" />
                            @error("items.$index.remarks")
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
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
                    <flux:button variant="ghost" wire:navigate href="{{ route('fixed-asset.index') }}">
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
