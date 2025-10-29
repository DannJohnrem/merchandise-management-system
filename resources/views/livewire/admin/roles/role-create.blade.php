<div class="space-y-8">

    {{-- Add Role Section --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl shadow p-6 space-y-6">
        <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Add New Role</h2>

        <form wire:submit.prevent="save" class="space-y-5">
            {{-- Role Name --}}
            <flux:field>
                <flux:label for="name">Role Name</flux:label>
                <flux:input id="name" wire:model.defer="name" placeholder="Enter role name..." />
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </flux:field>

            {{-- Permissions --}}
            <flux:field>
                <flux:label>Permissions</flux:label>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                    @foreach($permissions as $permission)
                        <label class="flex items-center space-x-2">
                            <flux:checkbox
                                wire:model="selectedPermissions"
                                value="{{ $permission->name }}"
                            />
                            <span class="text-sm">{{ ucfirst(str_replace('_', ' ', $permission->name)) }}</span>
                        </label>
                    @endforeach
                </div>
            </flux:field>

            <div class="pt-3">
                <flux:button type="submit" variant="primary" class="w-full sm:w-auto">
                    Create Role
                </flux:button>
            </div>
        </form>
    </div>

    {{-- Role List --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl shadow p-6">
        <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Existing Roles</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border border-gray-300 dark:border-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="p-3 border border-gray-300 dark:border-gray-700 text-left">Role Name</th>
                        <th class="p-3 border border-gray-300 dark:border-gray-700 text-left">Permissions</th>
                        <th class="p-3 border border-gray-300 dark:border-gray-700 text-center w-24">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="p-3 border border-gray-300 dark:border-gray-700 font-medium text-gray-800 dark:text-gray-100">
                                {{ $role->name }}
                            </td>
                            <td class="p-3 border border-gray-300 dark:border-gray-700">
                                @if($role->permissions->isNotEmpty())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($role->permissions as $perm)
                                            <span class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-2 py-0.5 rounded text-xs">
                                                {{ $perm->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">No permissions</span>
                                @endif
                            </td>
                            <td class="p-3 border border-gray-300 dark:border-gray-700 text-center">
                                <flux:button
                                    size="sm"
                                    variant="danger"
                                    wire:click="deleteRole({{ $role->id }})"
                                    onclick="confirm('Are you sure you want to delete this role?') || event.stopImmediatePropagation()"
                                >
                                    Delete
                                </flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-3 text-center text-gray-500 dark:text-gray-400">
                                No roles found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
