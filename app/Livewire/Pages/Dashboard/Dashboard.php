<?php

namespace App\Livewire\Pages\Dashboard;

use App\Models\ItLeasing;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    // Adjust these if your actual status values are different
    protected array $statuses = ['available', 'deployed', 'in_repair', 'returned', 'lost'];

    public function render()
    {
        // ---- IT Leasing status counts ----
        $itLeasingRaw = ItLeasing::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $itLeasingStatusCounts = collect($this->statuses)
            ->mapWithKeys(fn ($status) => [$status => (int) ($itLeasingRaw[$status] ?? 0)]);

        // ---- Top-level totals ----
        $totalAvailable = $itLeasingStatusCounts['available'] ?? 0;
        $totalDeployed = $itLeasingStatusCounts['deployed'] ?? 0;

        // ---- Total Amount to Bill this month (STATIC placeholder for now) ----
        // TODO: replace with real computation once billing/rental logic is defined.
        $totalBillThisMonth = null; // null = "not yet available" in the UI

        // ---- Users count ----
        $usersCount = User::count();

        // ---- Recent items list (last 10) ----
        $itLeasingItems = ItLeasing::latest()->limit(10)->get();

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
            'totalAvailable' => $totalAvailable,
            'totalDeployed' => $totalDeployed,
            'totalBillThisMonth' => $totalBillThisMonth,
            'usersCount' => $usersCount,
            'itLeasingItems' => $itLeasingItems,
            'availablePerModel' => $availablePerModel,
            'laptopStatusCounts' => $laptopStatusCounts,
            'laptopsPerMonth' => $laptopsPerMonth,
        ]);
    }
}
