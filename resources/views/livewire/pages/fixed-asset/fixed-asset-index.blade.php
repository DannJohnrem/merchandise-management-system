<div class="space-y-4 w-full">
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('fixed-asset.index') }}" wire:navigate>
            Fixed Assets
        </flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <x-page-heading>
        <x-slot:title>Fixed Asset Management</x-slot:title>
        <x-slot:subtitle>Manage fixed asset information here</x-slot:subtitle>
    </x-page-heading>

    <div class="flex justify-end">
        <flux:button icon="plus" variant="primary" href="{{ route('fixed-asset.create') }}" wire:navigate>
            Create Fixed Asset
        </flux:button>
    </div>

    <div class="w-full border rounded-lg p-4 bg-white dark:bg-zinc-800 shadow-sm">

        {{-- 🔥 Trigger load AFTER first render --}}
        <div wire:init="load">

            {{-- 🦴 SKELETON --}}
            @unless ($readyToLoad)
                <flux:skeleton.group animate="shimmer" class="space-y-4">

                    {{-- top controls skeleton (search / filters / per-page vibe) --}}
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2 w-full">
                            <div class="h-10 w-64"><flux:skeleton.line /></div>
                            <div class="h-10 w-40 hidden sm:block"><flux:skeleton.line /></div>
                            <div class="h-10 w-40 hidden md:block"><flux:skeleton.line /></div>
                        </div>
                        <div class="h-10 w-28"><flux:skeleton.line /></div>
                    </div>

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

                    {{-- pagination skeleton --}}
                    <div class="flex items-center justify-between pt-2">
                        <div class="h-4 w-44"><flux:skeleton.line /></div>
                        <div class="flex items-center gap-2">
                            @foreach (range(1, 4) as $i)
                                <div class="h-9 w-9"><flux:skeleton.line class="rounded-md" /></div>
                            @endforeach
                        </div>
                    </div>
                </flux:skeleton.group>
            @endunless

            {{-- ✅ REAL TABLE --}}
            @if ($readyToLoad)
                <livewire:pages.fixed-asset.fixed-asset-table :readyToLoad="$readyToLoad" />
            @endif

        </div>
    </div>


    <livewire:pages.fixed-asset.fixed-asset-qr-modal />
</div>

<script>
    // Remove old listener if already registered
    document.removeEventListener('confirm-delete-fixed-asset', window.__confirmDeleteFixedAssetHandler);

    // Define global handler
    window.__confirmDeleteFixedAssetHandler = function(event) {
        const id = event.detail.id;
        if (confirm('Are you sure you want to delete this fixed asset?')) {
            Livewire.dispatch('confirmDeleteFixedAsset', {
                id
            });
        }
    };

    // Add event listener only once
    document.addEventListener('confirm-delete-fixed-asset', window.__confirmDeleteFixedAssetHandler);
</script>
