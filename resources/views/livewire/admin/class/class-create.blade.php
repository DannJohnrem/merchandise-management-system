<div class="space-y-6">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.class.index') }}" wire:navigate>
            Classes
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>
            Create
        </flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Page Title --}}
    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Add Class</h1>

    {{-- Create Form --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow rounded-lg p-6">
        <form wire:submit.prevent="save" class="space-y-4">

            {{-- Name --}}
            <div>
                <flux:label>Name <span class="text-red-500">*</span></flux:label>
                <flux:input type="text" wire:model.defer="name" placeholder="Enter class name" />
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
                <flux:textarea wire:model.defer="description" placeholder="Optional description"></flux:textarea>
            </div>

            {{-- Code --}}
            <div>
                <flux:label>Code</flux:label>
                <flux:input type="text" wire:model.defer="code" placeholder="Optional code (ex: A57-2)" />
            </div>

            {{-- Department --}}
            <div>
                <flux:label>Department</flux:label>
                <flux:input type="text" wire:model.defer="department" placeholder="Department name" />
            </div>

            {{-- Position --}}
            <div>
                <flux:label>Position</flux:label>
                <flux:input type="text" wire:model.defer="position" placeholder="Position title" />
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end space-x-2">
                <flux:button variant="ghost" wire:navigate href="{{ route('admin.class.index') }}">
                    Cancel
                </flux:button>
                <flux:button variant="primary" type="submit">
                    Save
                </flux:button>
            </div>

        </form>
    </div>
</div>
