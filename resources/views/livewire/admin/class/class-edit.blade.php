<div class="space-y-6">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.class.index') }}" wire:navigate>
            Classes
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>
            Edit
        </flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Page Title --}}
    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Edit Class</h1>

    {{-- Edit Form --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow rounded-lg p-6">
        <form wire:submit.prevent="update" class="space-y-4">

            {{-- Name --}}
            <div>
                <flux:label>Name <span class="text-red-500">*</span></flux:label>
                <flux:input type="text" wire:model.defer="name" />
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Type --}}
            <div>
                <flux:label>Type</flux:label>
                <flux:select wire:model.defer="type">
                    <option value="">Select Type</option>
                    <option value="Department">Department</option>
                    <option value="Employee">Employee</option>
                    <option value="Client">Client</option>
                    <option value="Others">Others</option>
                </flux:select>
                @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Description --}}
            <div>
                <flux:label>Description</flux:label>
                <flux:textarea wire:model.defer="description"></flux:textarea>
            </div>

            {{-- Code --}}
            <div>
                <flux:label>Code</flux:label>
                <flux:input type="text" wire:model.defer="code" />
            </div>

            {{-- Department --}}
            <div>
                <flux:label>Department</flux:label>
                <flux:input type="text" wire:model.defer="department" />
            </div>

            {{-- Position --}}
            <div>
                <flux:label>Position</flux:label>
                <flux:input type="text" wire:model.defer="position" />
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end space-x-2">
                <flux:button variant="ghost" wire:navigate href="{{ route('admin.class.index') }}">
                    Cancel
                </flux:button>
                <flux:button variant="primary" type="submit">
                    Update
                </flux:button>
            </div>

        </form>
    </div>
</div>
