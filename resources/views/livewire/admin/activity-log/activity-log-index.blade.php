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
        <livewire:admin.activity-log.activity-logs-table />
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
