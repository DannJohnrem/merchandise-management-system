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
            <flux:button variant="outline" href="{{ route('it-leasing.edit', $itLeasing) }}" wire:navigate>
                Edit Item
            </flux:button>
            <flux:spacer />
            <flux:button variant="danger" onclick="confirm('Are you sure?') && $wire.call('delete', {{ $itLeasing->id }})">
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
                        <dd>{{ $itLeasing->formatted_purchase_date ?? 'N/A' }}</dd> {{-- Format in model/controller --}}
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
                                    'deployed'  => ['blue', 'dark:bg-blue-500 dark:text-white'],
                                    'in_repair' => ['yellow', 'dark:bg-yellow-500 dark:text-black'],
                                    'returned'  => ['purple', 'dark:bg-purple-500 dark:text-white'],
                                    'lost'      => ['red', 'dark:bg-red-500 dark:text-white'],
                                ];

                                $labels = [
                                    'available' => 'Available',
                                    'deployed'  => 'Deployed',
                                    'in_repair' => 'For Repair',
                                    'returned'  => 'Returned',
                                    'lost'      => 'Lost',
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
                                    'new'  => ['green', 'dark:bg-green-500 dark:text-white'],
                                    'good' => ['blue', 'dark:bg-blue-500 dark:text-white'],
                                    'fair' => ['yellow', 'dark:bg-yellow-500 dark:text-black'],
                                    'poor' => ['red', 'dark:bg-red-500 dark:text-white'],
                                ];

                                $labels = [
                                    'new'  => 'New',
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
</div>
