<div class="space-y-4 w-full">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.activity-log.index') }}" wire:navigate>
            Activity Logs
        </flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Page Heading --}}
    <x-page-heading>
        <x-slot:title>Activity Logs</x-slot:title>
        <x-slot:subtitle>View all actions and changes for Fixed Assets and IT Leasing</x-slot:subtitle>
    </x-page-heading>

    {{-- Optional top actions --}}
    <div class="flex justify-end">
        {{-- Example: could add export button or filter --}}
        {{-- <flux:button icon="arrow-down-tray" variant="primary">
            Export Logs
        </flux:button> --}}
    </div>

    {{-- DataTable Card --}}
    <div class="w-full border rounded-lg p-4 bg-white dark:bg-zinc-800 shadow-sm">

        {{-- Trigger load AFTER first render --}}
        <div wire:init="load">

            {{-- SKELETON --}}
            @unless ($readyToLoad)
                <flux:skeleton.group animate="shimmer">
                    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">

                        {{-- header --}}
                        <div class="grid grid-cols-6 gap-4 px-4 py-3 bg-zinc-50 dark:bg-zinc-900/40">
                            @foreach (range(1, 6) as $i)
                                <div class="h-4">
                                    <flux:skeleton.line style="width: {{ rand(50, 80) }}%" />
                                </div>
                            @endforeach
                        </div>

                        {{-- rows --}}
                        @foreach (range(1, 8) as $row)
                            <div class="grid grid-cols-6 gap-4 px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
                                @foreach (range(1, 6) as $col)
                                    <div class="h-4">
                                        <flux:skeleton.line style="width: {{ rand(45, 90) }}%" />
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                    </div>
                </flux:skeleton.group>
            @endunless

            {{-- REAL TABLE --}}
            @if ($readyToLoad)
                <livewire:admin.activity-log.activity-logs-table />
            @endif

        </div>
    </div>
</div>

{{-- Optional: Delete / Action Confirmation JS --}}
<script>
    // Example template if needed for global confirmation events
    // Replace or remove if not needed
    // document.removeEventListener('confirm-delete-log', window.__confirmDeleteLogHandler);
    // window.__confirmDeleteLogHandler = function (event) {
    //     const id = event.detail.id;
    //     if (confirm('Are you sure you want to delete this log?')) {
    //         Livewire.dispatch('confirmDeleteLog', { id });
    //     }
    // };
    // document.addEventListener('confirm-delete-log', window.__confirmDeleteLogHandler);
</script>
