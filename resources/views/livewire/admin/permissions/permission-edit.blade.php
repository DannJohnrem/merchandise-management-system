<div class="space-y-6">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.permissions.index') }}" wire:navigate>Permissions</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Edit Permission</h1>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow rounded-lg p-6">
        <form wire:submit.prevent="update" class="space-y-5">
            {{-- Permission Name --}}
            <div>
                <flux:label for="name">
                    Permission Name <span class="text-red-500">*</span>
                </flux:label>
                <flux:input id="name" type="text" wire:model.defer="name" placeholder="Enter permission name..." />
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end space-x-2 pt-3">
                <flux:button variant="ghost" wire:navigate href="{{ route('admin.permissions.index') }}">
                    Cancel
                </flux:button>
                <flux:button type="submit" variant="primary">
                    Update
                </flux:button>
            </div>
        </form>
    </div>
</div>
