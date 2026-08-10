<?php

namespace App\Livewire\Pages\Dashboard;

use App\Models\FixedAsset;
use App\Models\ItLeasing;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    // Adjust these if your actual status values are different
    protected array $statuses = ['available', 'deployed', 'in_repair', 'returned', 'lost'];

    // Modal state
    public bool $showUnitsModal = false;
    public string $modalStatus = '';
    public string $modalTitle = '';

    public function showUnitsByStatus(string $status): void
    {
        $this->modalStatus = $status;
        $this->modalTitle = match ($status) {
            'available' => 'Available Units',
            'deployed' => 'Deployed Units',
            default => ucfirst(str_replace('_', ' ', $status)) . ' Units',
        };
        $this->showUnitsModal = true;
    }

    public function getModalUnitsProperty()
    {
        if (! $this->showUnitsModal || $this->modalStatus === '') {
            return collect();
        }

        return ItLeasing::where('status', $this->modalStatus)
            ->select(['id', 'item_name', 'model', 'serial_number', 'charger_serial_number'])
            ->orderBy('item_name')
            ->get();
    }

    public function render()
    {
        // ---- IT Leasing status counts ----
        $itLeasingRaw = ItLeasing::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $itLeasingStatusCounts = collect($this->statuses)
            ->mapWithKeys(fn ($status) => [$status => (int) ($itLeasingRaw[$status] ?? 0)]);

        // ---- Fixed Asset status counts ----
        $fixedAssetRaw = FixedAsset::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $fixedAssetStatusCounts = collect($this->statuses)
            ->mapWithKeys(fn ($status) => [$status => (int) ($fixedAssetRaw[$status] ?? 0)]);

        // ---- Users count ----
        $usersCount = User::count();

        // ---- Recent items lists (last 10) ----
        $itLeasingItems = ItLeasing::latest()->limit(10)->get();

        // ---- Top-level totals ----
        $totalAvailable = $itLeasingStatusCounts['available'] ?? 0;
        $totalDeployed = $itLeasingStatusCounts['deployed'] ?? 0;

        // ---- Total Amount to Bill this month (STATIC placeholder for now) ----
        $totalBillThisMonth = null;

        // ---- Available units per model ----
        $availablePerModel = ItLeasing::where('status', 'available')
            ->whereNotNull('model')
            ->selectRaw('model, COUNT(*) as total')
            ->groupBy('model')
            ->orderByDesc('total')
            ->get();

        // ---- Pie chart: laptops by status (filtered by 'category' column) ----
        $laptopStatusRaw = ItLeasing::where('category', 'Laptop')
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $laptopStatusCounts = collect($this->statuses)
            ->mapWithKeys(fn ($status) => [$status => (int) ($laptopStatusRaw[$status] ?? 0)])
            ->filter(fn ($total) => $total > 0);

        // ---- Line chart: laptops purchased per month (based on purchase_date) ----
        $laptopsPerMonthRaw = ItLeasing::where('category', 'Laptop')
            ->whereNotNull('purchase_date')
            ->selectRaw("DATE_FORMAT(purchase_date, '%Y-%m') as ym, COUNT(*) as total")
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('total', 'ym');

        $laptopsPerMonth = [
            'labels' => $laptopsPerMonthRaw->keys()
                ->filter()
                ->map(fn ($ym) => \Carbon\Carbon::createFromFormat('Y-m', $ym)->format('M Y'))
                ->values(),
            'data' => $laptopsPerMonthRaw->values(),
        ];

        return view('livewire.pages.dashboard.dashboard', [
            'itLeasingStatusCounts' => $itLeasingStatusCounts,
            'fixedAssetStatusCounts' => $fixedAssetStatusCounts,
            'usersCount' => $usersCount,
            'itLeasingItems' => $itLeasingItems,
            'totalAvailable' => $totalAvailable,
            'totalDeployed' => $totalDeployed,
            'totalBillThisMonth' => $totalBillThisMonth,
            'availablePerModel' => $availablePerModel,
            'laptopStatusCounts' => $laptopStatusCounts,
            'laptopsPerMonth' => $laptopsPerMonth,
        ]);
    }
}
