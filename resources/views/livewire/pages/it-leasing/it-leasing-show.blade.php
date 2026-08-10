<div class="space-y-6 w-full">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('it-leasing.index') }}" wire:navigate>
            IT Leasing
        </flux:breadcrumbs.item>
        <flux:breadcrumbs.item>
            {{ $itLeasing->item_name }}
        </flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Page Heading with Actions --}}
    <x-page-heading>
        <x-slot:title>{{ $itLeasing->item_name }}</x-slot:title>
        <x-slot:subtitle>View details of this IT leased item</x-slot:subtitle>
        <x-slot:actions>
            <flux:button variant="ghost" icon="arrow-left" href="{{ $redirectUrl }}" wire:navigate>
                Back
            </flux:button>
            <flux:button variant="outline"
                href="{{ route('it-leasing.edit', ['item' => $itLeasing, 'redirect' => $redirectUrl]) }}" wire:navigate>
                Edit Item
            </flux:button>
            <flux:button variant="outline" onclick="Flux.modal('pull-out-form').show()">
                Pull Out / Replacement Form
            </flux:button>
            <flux:spacer />
            <flux:button variant="danger"
                onclick="confirm('Are you sure?') && $wire.call('delete', {{ $itLeasing->id }})">
                Delete Item
            </flux:button>
        </x-slot:actions>
    </x-page-heading>

    {{-- Item Details Card --}}
    <div class="w-full bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Item Details</h2>
        </div>
        <div class="p-6 space-y-6">
            {{-- Basic Info Section --}}
            <div>
                <h3 class="text-base font-medium text-zinc-900 dark:text-zinc-100 mb-4">Basic Information</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-tag class="w-5 h-5 text-zinc-500" aria-label="Category" />
                        <dt class="font-medium text-zinc-700 dark:text-zinc-300">Category:</dt>
                        <dd>{{ $itLeasing->category ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-device-phone-mobile class="w-5 h-5 text-zinc-500" aria-label="Item Name" />
                        <dt class="font-medium text-zinc-700 dark:text-zinc-300">Item Name:</dt>
                        <dd>{{ $itLeasing->item_name ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-hashtag class="w-5 h-5 text-zinc-500" aria-label="Serial Number" />
                        <dt class="font-medium text-zinc-700 dark:text-zinc-300">Serial Number:</dt>
                        <dd class="break-all">{{ $itLeasing->serial_number ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-battery-100 class="w-5 h-5 text-zinc-500" aria-label="Charger Serial Number" />
                        <dt class="font-medium text-zinc-700 dark:text-zinc-300">Charger Serial Number:</dt>
                        <dd class="break-all">{{ $itLeasing->charger_serial_number ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-building-storefront class="w-5 h-5 text-zinc-500" aria-label="Brand" />
                        <dt class="font-medium text-zinc-700 dark:text-zinc-300">Brand:</dt>
                        <dd>{{ $itLeasing->brand ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-cog class="w-5 h-5 text-zinc-500" aria-label="Model" />
                        <dt class="font-medium text-zinc-700 dark:text-zinc-300">Model:</dt>
                        <dd>{{ $itLeasing->model ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-currency-dollar class="w-5 h-5 text-zinc-500" aria-label="Purchase Cost" />
                        <dt class="font-medium text-zinc-700 dark:text-zinc-300">Purchase Cost:</dt>
                        <dd>₱{{ number_format($itLeasing->purchase_cost ?? 0, 2) }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Purchase Info Section --}}
            <div>
                <h3 class="text-base font-medium text-zinc-900 dark:text-zinc-100 mb-4">Purchase Information</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-truck class="w-5 h-5 text-zinc-500" aria-label="Supplier" />
                        <dt class="font-medium text-zinc-700 dark:text-zinc-300">Supplier:</dt>
                        <dd>{{ $itLeasing->supplier ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-document-text class="w-5 h-5 text-zinc-500" aria-label="Purchase Order #" />
                        <dt class="font-medium text-zinc-700 dark:text-zinc-300">Purchase Order #:</dt>
                        <dd>{{ $itLeasing->purchase_order_no ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-calendar class="w-5 h-5 text-zinc-500" aria-label="Purchase Date" />
                        <dt class="font-medium text-zinc-700 dark:text-zinc-300">Purchase Date:</dt>
                        <dd>{{ $itLeasing->purchase_date?->format('Y-m-d') ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-shield-check class="w-5 h-5 text-zinc-500" aria-label="Warranty Expiration" />
                        <dt class="font-medium text-zinc-700 dark:text-zinc-300">Warranty Expiration:</dt>
                        <dd>{{ $itLeasing->formatted_warranty_expiration ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Assignment Info Section --}}
            <div>
                <h3 class="text-base font-medium text-zinc-900 dark:text-zinc-100 mb-4">Assignment & Status</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-building-office class="w-5 h-5 text-zinc-500" aria-label="Assigned Company" />
                        <dt class="font-medium text-zinc-700 dark:text-zinc-300">Assigned Company:</dt>
                        <dd>{{ $itLeasing->assigned_company ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-user class="w-5 h-5 text-zinc-500" aria-label="Assigned Employee" />
                        <dt class="font-medium text-zinc-700 dark:text-zinc-300">Assigned Employee:</dt>
                        <dd>{{ $itLeasing->assigned_employee ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-map-pin class="w-5 h-5 text-zinc-500" aria-label="Location" />
                        <dt class="font-medium text-zinc-700 dark:text-zinc-300">Location:</dt>
                        <dd>{{ $itLeasing->location ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-check-circle class="w-5 h-5 text-zinc-500" aria-label="Status" />
                        <dt class="font-medium text-zinc-700 dark:text-zinc-300">Status:</dt>
                        <dd>
                            @php
                                $key = strtolower(trim($itLeasing->status ?? '')); // default to empty string if null
                                $colors = [
                                    'available' => ['green', 'dark:bg-green-500 dark:text-white'],
                                    'deployed' => ['blue', 'dark:bg-blue-500 dark:text-white'],
                                    'in_repair' => ['yellow', 'dark:bg-yellow-500 dark:text-black'],
                                    'returned' => ['purple', 'dark:bg-purple-500 dark:text-white'],
                                    'lost' => ['red', 'dark:bg-red-500 dark:text-white'],
                                ];

                                $labels = [
                                    'available' => 'Available',
                                    'deployed' => 'Deployed',
                                    'in_repair' => 'For Repair',
                                    'returned' => 'Returned',
                                    'lost' => 'Lost',
                                ];

                                [$color, $class] = $colors[$key] ?? ['slate', 'dark:bg-slate-500 dark:text-white'];
                                $display = $labels[$key] ?? '—'; // show dash if status unknown
                            @endphp
                            <flux:badge color="{{ $color }}" class="{{ $class }}">
                                {{ $display }}
                            </flux:badge>
                        </dd>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-wrench-screwdriver class="w-5 h-5 text-zinc-500" aria-label="Condition" />
                        <dt class="font-medium text-zinc-700 dark:text-zinc-300">Condition:</dt>
                        <dd>
                            @php
                                $key = strtolower(trim($itLeasing->condition ?? '')); // default to empty string if null
                                $colors = [
                                    'new' => ['green', 'dark:bg-green-500 dark:text-white'],
                                    'good' => ['blue', 'dark:bg-blue-500 dark:text-white'],
                                    'fair' => ['yellow', 'dark:bg-yellow-500 dark:text-black'],
                                    'poor' => ['red', 'dark:bg-red-500 dark:text-white'],
                                ];

                                $labels = [
                                    'new' => 'New',
                                    'good' => 'Good',
                                    'fair' => 'Fair',
                                    'poor' => 'Poor',
                                ];

                                [$color, $class] = $colors[$key] ?? ['slate', 'dark:bg-slate-500 dark:text-white'];
                                $display = $labels[$key] ?? '—'; // show dash if condition unknown
                            @endphp
                            <flux:badge color="{{ $color }}" class="{{ $class }}">
                                {{ $display }}
                            </flux:badge>
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Remarks and Inclusions --}}
            @if ($itLeasing->remarks || !empty($itLeasing->inclusions))
                <div>
                    <h3 class="text-base font-medium text-zinc-900 dark:text-zinc-100 mb-4">Additional Notes</h3>
                    <div class="space-y-2">
                        @if ($itLeasing->remarks)
                            <p><strong>Remarks:</strong> {{ $itLeasing->remarks }}</p>
                        @endif
                        @if (!empty($itLeasing->inclusions))
                            <div class="flex flex-wrap gap-2">
                                <strong>Inclusions:</strong>
                                @foreach ($itLeasing->inclusions as $inclusion)
                                    <flux:badge variant="success">{{ $inclusion }}</flux:badge>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Status History Card --}}
    <div class="w-full bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-sm"
        x-data="{ showAll: false }">
        <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Status History</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">Timeline of status changes for this item</p>
            </div>
            <flux:badge color="zinc" size="sm">{{ $itLeasing->statusHistories->count() }}
                {{ Str::plural('entry', $itLeasing->statusHistories->count()) }}</flux:badge>
        </div>

        <div class="p-6 space-y-3">
            @php
                $statusMeta = [
                    'available' => ['icon' => 'check-circle', 'bg' => 'bg-emerald-500', 'badge' => 'emerald'],
                    'deployed' => ['icon' => 'arrow-up-tray', 'bg' => 'bg-blue-500', 'badge' => 'blue'],
                    'in_repair' => ['icon' => 'wrench-screwdriver', 'bg' => 'bg-amber-500', 'badge' => 'amber'],
                    'returned' => ['icon' => 'arrow-uturn-left', 'bg' => 'bg-zinc-400', 'badge' => 'zinc'],
                    'lost' => ['icon' => 'x-circle', 'bg' => 'bg-red-500', 'badge' => 'red'],
                ];
                $defaultMeta = ['icon' => 'question-mark-circle', 'bg' => 'bg-zinc-400', 'badge' => 'zinc'];
                $visibleLimit = 5;
            @endphp

            @forelse ($itLeasing->statusHistories as $index => $history)
                @php
                    $meta = $statusMeta[$history->to_status] ?? $defaultMeta;
                    $fromMeta = $statusMeta[$history->from_status] ?? $defaultMeta;
                @endphp

                <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-4"
                    @if ($index >= $visibleLimit) x-show="showAll" x-cloak @endif>
                    <div class="flex items-start gap-3">
                        <div
                            class="flex-shrink-0 flex items-center justify-center size-9 rounded-full {{ $meta['bg'] }}">
                            <flux:icon :name="$meta['icon']" class="size-4.5 text-white" />
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center justify-between gap-x-3 gap-y-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    @if ($history->from_status)
                                        <flux:badge size="sm" :color="$fromMeta['badge']" class="capitalize">
                                            {{ str_replace('_', ' ', $history->from_status) }}
                                        </flux:badge>
                                        <x-heroicon-o-arrow-right class="w-4 h-4 text-zinc-400 flex-shrink-0" />
                                    @endif
                                    <flux:badge size="sm" :color="$meta['badge']" class="capitalize">
                                        {{ str_replace('_', ' ', $history->to_status) }}
                                    </flux:badge>
                                </div>

                                <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400 whitespace-nowrap">
                                    {{ $history->changed_at->format('M d, Y g:i A') }}
                                </span>
                            </div>

                            <div class="flex items-center gap-1.5 mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                                <x-heroicon-o-user class="w-4 h-4" />
                                <span>{{ $history->changedBy?->name ?? 'System' }}</span>
                                <span class="text-zinc-300 dark:text-zinc-600">•</span>
                                <span>{{ $history->changed_at->diffForHumans() }}</span>
                            </div>

                            @if ($history->remarks)
                                <p
                                    class="mt-3 text-sm text-zinc-700 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-900/50 border border-zinc-100 dark:border-zinc-700 rounded-md px-3 py-2">
                                    {{ $history->remarks }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <x-heroicon-o-clock class="w-8 h-8 text-zinc-300 dark:text-zinc-600 mx-auto mb-2" />
                    <p class="text-sm text-zinc-400">No status changes recorded yet.</p>
                </div>
            @endforelse

            @if ($itLeasing->statusHistories->count() > $visibleLimit)
                <button type="button" @click="showAll = !showAll"
                    class="w-full flex items-center justify-center gap-2 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-zinc-100 border border-dashed border-zinc-300 dark:border-zinc-600 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-900/50 transition">
                    <span x-show="!showAll">Show {{ $itLeasing->statusHistories->count() - $visibleLimit }} more
                        entries</span>
                    <span x-show="showAll" x-cloak>Show less</span>
                    <x-heroicon-o-chevron-down class="w-4 h-4 transition-transform"
                        x-bind:class="showAll ? 'rotate-180' : ''" />
                </button>
            @endif
        </div>
    </div>

    <livewire:pages.it-leasing.generate-pull-out-modal :it-leasing="$itLeasing" />
</div>
