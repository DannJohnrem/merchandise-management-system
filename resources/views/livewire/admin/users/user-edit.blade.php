<div class="space-y-6">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.users.index') }}" wire:navigate>Users</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Edit</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Edit User</h1>

    <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow rounded-lg p-6">
        <form wire:submit.prevent="update" class="space-y-4">
            {{-- Name --}}
            <div>
                <flux:label for="name">
                    Name <span class="text-red-500">*</span>
                </flux:label>
                <flux:input id="name" type="text" wire:model.defer="name" placeholder="Enter full name" />
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <flux:label for="email">
                    Email <span class="text-red-500">*</span>
                </flux:label>
                <flux:input id="email" type="email" wire:model.defer="email" placeholder="Enter email address" />
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Password (Optional) --}}
            <div>
                <flux:label for="password">
                    Password <span class="text-gray-400 text-sm">(Leave blank to keep current)</span>
                </flux:label>
                <flux:input id="password" type="password" wire:model.defer="password" placeholder="Enter new password" />
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Role --}}
            <div>
                <flux:label for="role">
                    Role <span class="text-red-500">*</span>
                </flux:label>
                <flux:select id="role" wire:model.defer="role">
                    <option value="">Select Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                    @endforeach
                </flux:select>
                @error('role')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end space-x-2">
                <flux:button variant="ghost" wire:navigate href="{{ route('admin.users.index') }}">Cancel</flux:button>
                <flux:button variant="primary" type="submit">Update</flux:button>
            </div>
        </form>
    </div>
</div>


