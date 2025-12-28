<flux:modal name="activity-log-details" class="md:w-96" :closable="true" :dismissible="true">
    <div class="space-y-4 p-4">

        <x-slot:title>Activity Log Details</x-slot:title>

        {{-- Loading state --}}
        <div wire:loading.flex wire:target="fetchLog" class="justify-center items-center min-h-[150px]">
            <svg class="animate-spin h-6 w-6 text-gray-500"
                 xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10"
                        stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
        </div>

        {{-- Activity Log Details --}}
        @if ($selectedLog)
            <div class="space-y-2 text-sm">
                <p><strong>Module:</strong> {{ $selectedLog->subject_type }}</p>
                <p><strong>Item ID:</strong> {{ $selectedLog->subject_id }}</p>
                <p><strong>Action:</strong> {{ ucfirst($selectedLog->action) }}</p>
                <p><strong>Description:</strong> {{ $selectedLog->description }}</p>

                <div class="flex gap-4">
                    <div class="w-1/2">
                        <p class="font-semibold">Old Values:</p>
                        <pre class="text-xs bg-gray-100 dark:bg-gray-800 p-2 rounded overflow-auto">
{{ json_encode($selectedLog->old_values, JSON_PRETTY_PRINT) }}
                        </pre>
                    </div>

                    <div class="w-1/2">
                        <p class="font-semibold">New Values:</p>
                        <pre class="text-xs bg-gray-100 dark:bg-gray-800 p-2 rounded overflow-auto">
{{ json_encode($selectedLog->new_values, JSON_PRETTY_PRINT) }}
                        </pre>
                    </div>
                </div>

                <p><strong>User:</strong> {{ $selectedLog->user?->name ?? 'System' }}</p>
                <p><strong>Created At:</strong> {{ $selectedLog->created_at->format('Y-m-d H:i') }}</p>
            </div>
        @endif

        {{-- Cancel button --}}
        <div class="flex justify-end mt-4">
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
        </div>
    </div>
</flux:modal>
