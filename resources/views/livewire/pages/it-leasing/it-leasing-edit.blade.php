<div class="space-y-6">

    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('pages.it-leasing.index') }}" wire:navigate>
            IT Leasing
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Page Title --}}
    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
        Edit IT Leasing Item
    </h1>

    {{-- Form Card --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow rounded-lg p-6">

        <form wire:submit.prevent="update" class="space-y-6">

            {{-- 2 Columns --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Category --}}
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
                    @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Serial Number --}}
                <div>
                    <flux:label for="serial_number">Serial Number *</flux:label>
                    <flux:input id="serial_number" wire:model.defer="serial_number" />
                    @error('serial_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Brand --}}
                <div>
                    <flux:label for="brand">Brand</flux:label>
                    <flux:input id="brand" wire:model.defer="brand" />
                    @error('brand') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Model --}}
                <div>
                    <flux:label for="model">Model</flux:label>
                    <flux:input id="model" wire:model.defer="model" />
                    @error('model') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Cost --}}
                <div>
                    <flux:label for="cost">Cost</flux:label>
                    <flux:input id="cost" type="number" step="0.01" wire:model.defer="cost" />
                    @error('cost') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Assigned To --}}
                <div>
                    <flux:label for="assigned_to">Assigned To</flux:label>
                    <flux:input id="assigned_to" wire:model.defer="assigned_to" />
                    @error('assigned_to') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Class --}}
                <div>
                    <flux:label for="class">Class (Employee)</flux:label>
                    <flux:input id="class" wire:model.defer="class" />
                    @error('class') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Status --}}
                <div>
                    <flux:label for="status">Status *</flux:label>
                    <flux:select id="status" wire:model.defer="status">
                        <option value="in_use">In Use</option>
                        <option value="returned">Returned</option>
                        <option value="repair">For Repair</option>
                    </flux:select>
                    @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- QR Code --}}
                <div class="md:col-span-2">
                    <flux:label for="qr_code_path">QR Code (optional)</flux:label>
                    <flux:input id="qr_code_path" type="file" wire:model="qr_code_path" />
                    @error('qr_code_path') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    {{-- Show preview if exists --}}
                    @if ($qrPreview)
                        <img src="{{ $qrPreview }}" class="h-32 mt-2 rounded border" />
                    @endif
                </div>

                {{-- Remarks --}}
                <div class="md:col-span-2">
                    <flux:label for="remarks">Remarks</flux:label>
                    <flux:textarea id="remarks" wire:model.defer="remarks" rows="4" />
                    @error('remarks') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

            </div>

            {{-- Buttons --}}
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
