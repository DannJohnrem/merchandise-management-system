<div class="space-y-6">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.roles.index') }}" wire:navigate>Roles</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Page Title --}}
    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Edit Role</h1>

    {{-- Edit Role Form --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow rounded-lg p-6">
        <form wire:submit.prevent="update" class="space-y-5">

            {{-- Role Name --}}
            <div>
                <flux:label for="name">
                    Role Name <span class="text-red-500">*</span>
                </flux:label>
                <flux:input id="name" type="text" wire:model.defer="name" placeholder="Enter role name..." />
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Permissions --}}
            <div>
                <flux:label>Permissions</flux:label>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 mt-2">
                    @foreach($permissions as $permission)
                        <label class="flex items-center space-x-2">
                            <flux:checkbox
                                wire:model="selectedPermissions"
                                value="{{ $permission->name }}"
                            />
                            <span class="text-sm text-gray-700 dark:text-gray-200">
                                {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('selectedPermissions')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end space-x-2 pt-3">
                <flux:button variant="ghost" wire:navigate href="{{ route('admin.roles.index') }}">
                    Cancel
                </flux:button>
                <flux:button type="submit" variant="primary">
                    Update
                </flux:button>
            </div>
        </form>
    </div>
</div>
