<div class="flex h-full w-full flex-1 flex-col gap-6">

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-dashboard.stat-card label="Total Users" :value="$usersCount" icon="users" color="indigo" />
        <x-dashboard.stat-card label="Total Available Units" :value="$totalAvailable" icon="check-circle" color="emerald" />
        <x-dashboard.stat-card label="Total Deployed Units" :value="$totalDeployed" icon="arrow-up-tray" color="blue" />

        {{-- Billing card is static for now --}}
        <div class="rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <div class="flex items-center justify-between">
                <div>
                    <flux:text size="sm" class="text-neutral-500 dark:text-neutral-400">Total Amount to Bill (This Month)</flux:text>
                    @if (is_null($totalBillThisMonth))
                        <p class="mt-1 text-lg font-semibold text-neutral-400 dark:text-neutral-500">Coming soon</p>
                    @else
                        <p class="mt-1 text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                            ₱{{ number_format($totalBillThisMonth, 2) }}
                        </p>
                    @endif
                </div>
                <div class="flex size-10 items-center justify-center rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-950 dark:text-amber-300">
                    <flux:icon name="banknotes" class="size-5" />
                </div>
            </div>
        </div>
    </div>

    {{-- AVAILABLE UNITS PER MODEL --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <flux:heading size="lg">Available Units per Model</flux:heading>
                    <flux:text size="sm" class="text-neutral-500">Current on-hand stock by model</flux:text>
                </div>
                <flux:icon name="chart-bar" class="size-5 text-neutral-400" />
            </div>
            @if ($availablePerModel->isEmpty())
                <div class="flex h-64 items-center justify-center text-sm text-neutral-400">
                    No available units yet.
                </div>
            @else
                <div wire:ignore x-data="modelBarChart(@js($availablePerModel))" x-init="init()" class="relative h-64">
                    <canvas x-ref="canvas"></canvas>
                </div>
            @endif
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <div class="mb-4 flex items-center justify-between">
                <flux:heading size="lg">Available Units per Model</flux:heading>
                <flux:badge color="zinc" size="sm">{{ $availablePerModel->count() }} models</flux:badge>
            </div>
            <div class="max-h-64 overflow-y-auto">
                <table class="w-full text-left text-sm">
                    <thead class="sticky top-0 bg-white dark:bg-neutral-900">
                        <tr class="border-b border-neutral-200 text-neutral-500 dark:border-neutral-700 dark:text-neutral-400">
                            <th class="py-2 pr-3 font-medium">Model</th>
                            <th class="py-2 pr-3 font-medium text-right">Available</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($availablePerModel as $row)
                            <tr class="border-b border-neutral-100 last:border-0 dark:border-neutral-800">
                                <td class="py-2 pr-3 text-neutral-800 dark:text-neutral-100">{{ $row->model }}</td>
                                <td class="py-2 pr-3 text-right font-medium text-emerald-600 dark:text-emerald-400">{{ $row->total }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="py-6 text-center text-neutral-400">No records yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CHARTS --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <flux:heading size="lg">Laptops by Status</flux:heading>
                    <flux:text size="sm" class="text-neutral-500">Current distribution</flux:text>
                </div>
                <flux:icon name="chart-pie" class="size-5 text-neutral-400" />
            </div>
            @if ($laptopStatusCounts->isEmpty())
                <div class="flex h-64 items-center justify-center text-sm text-neutral-400">
                    No laptop records yet.
                </div>
            @else
                <div wire:ignore x-data="pieChart(@js($laptopStatusCounts))" x-init="init()" class="relative h-64">
                    <canvas x-ref="canvas"></canvas>
                </div>
            @endif
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <flux:heading size="lg">Laptops Purchased per Month</flux:heading>
                    <flux:text size="sm" class="text-neutral-500">Based on purchase date</flux:text>
                </div>
                <flux:icon name="presentation-chart-line" class="size-5 text-neutral-400" />
            </div>
            @if (empty($laptopsPerMonth['labels']) || $laptopsPerMonth['labels']->isEmpty())
                <div class="flex h-64 items-center justify-center text-sm text-neutral-400">
                    No purchase date data yet.
                </div>
            @else
                <div wire:ignore x-data="lineChart(@js($laptopsPerMonth))" x-init="init()" class="relative h-64">
                    <canvas x-ref="canvas"></canvas>
                </div>
            @endif
        </div>
    </div>

    {{-- RECENT IT LEASING ITEMS --}}
    <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
        <div class="mb-4 flex items-center justify-between">
            <flux:heading size="lg">Recent IT Leasing Items</flux:heading>
            <flux:badge color="zinc" size="sm">Last 10</flux:badge>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-neutral-200 text-neutral-500 dark:border-neutral-700 dark:text-neutral-400">
                        <th class="py-2 pr-3 font-medium">Item</th>
                        <th class="py-2 pr-3 font-medium">Model</th>
                        <th class="py-2 pr-3 font-medium">Status</th>
                        <th class="py-2 pr-3 font-medium">Purchased</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($itLeasingItems as $item)
                        <tr class="border-b border-neutral-100 last:border-0 dark:border-neutral-800">
                            <td class="py-2.5 pr-3 font-medium text-neutral-800 dark:text-neutral-100">{{ $item->item_name }}</td>
                            <td class="py-2.5 pr-3 text-neutral-600 dark:text-neutral-300">{{ $item->model ?? '—' }}</td>
                            <td class="py-2.5 pr-3">
                                <flux:badge size="sm" :color="match($item->status) {
                                    'available' => 'emerald',
                                    'deployed' => 'blue',
                                    'in_repair' => 'amber',
                                    'returned' => 'zinc',
                                    'lost' => 'red',
                                    default => 'zinc',
                                }">
                                    {{ str_replace('_', ' ', $item->status) }}
                                </flux:badge>
                            </td>
                            <td class="py-2.5 pr-3 text-neutral-500 dark:text-neutral-400">
                                {{ $item->purchase_date?->format('M d, Y') ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-6 text-center text-neutral-400">No records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function pieChart(statusCounts) {
        return {
            chart: null,
            init() {
                const labels = Object.keys(statusCounts).map(s => s.replace('_', ' '));
                const data = Object.values(statusCounts);
                this.chart = new window.Chart(this.$refs.canvas, {
                    type: 'doughnut',
                    data: {
                        labels,
                        datasets: [{
                            data,
                            backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#64748b', '#ef4444'],
                            borderWidth: 0,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true } } },
                    },
                });
                document.addEventListener('livewire:navigating', () => this.chart?.destroy(), { once: true });
            },
        };
    }

    function lineChart(payload) {
        return {
            chart: null,
            init() {
                this.chart = new window.Chart(this.$refs.canvas, {
                    type: 'line',
                    data: {
                        labels: payload.labels,
                        datasets: [{
                            label: 'Laptops Purchased',
                            data: payload.data,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59,130,246,0.1)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 3,
                            pointBackgroundColor: '#3b82f6',
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
                    },
                });
                document.addEventListener('livewire:navigating', () => this.chart?.destroy(), { once: true });
            },
        };
    }

    function modelBarChart(rows) {
        return {
            chart: null,
            init() {
                const labels = rows.map(r => r.model);
                const data = rows.map(r => r.total);
                this.chart = new window.Chart(this.$refs.canvas, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Available',
                            data,
                            backgroundColor: '#10b981',
                            borderRadius: 6,
                            maxBarThickness: 36,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1 } },
                            x: { ticks: { autoSkip: false, maxRotation: 45, minRotation: 0 } },
                        },
                    },
                });
                document.addEventListener('livewire:navigating', () => this.chart?.destroy(), { once: true });
            },
        };
    }
</script>
