<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fixed Asset Info - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800 flex justify-center items-start p-6">
    <div class="max-w-lg w-full bg-white shadow-lg rounded-lg p-6 mt-10 dark:bg-zinc-900 dark:text-white">
        <h1 class="text-2xl font-bold mb-4">Fixed Asset Information</h1>

        <div class="space-y-3">
            <p><strong>Asset Tag:</strong> {{ $item->asset_tag ?? '-' }}</p>
            <p><strong>Category:</strong> {{ $item->category ?? '-' }}</p>
            <p><strong>Serial Number:</strong> {{ $item->serial_number ?? '-' }}</p>
            <p><strong>Brand:</strong> {{ $item->brand ?? '-' }}</p>
            <p><strong>Model:</strong> {{ $item->model ?? '-' }}</p>
            <p><strong>Cost:</strong> ₱{{ $item->cost ? number_format($item->cost, 2) : '-' }}</p>
            <p><strong>Supplier:</strong> {{ $item->supplier ?? '-' }}</p>
            <p><strong>Assigned To:</strong> {{ $item->assigned_to ?? '-' }}</p>
            <p><strong>Class:</strong> {{ $item->class ?? '-' }}</p>
            <p><strong>Location:</strong> {{ $item->location ?? '-' }}</p>
            <p><strong>Status:</strong> {{ ucfirst(str_replace('_',' ', $item->status)) ?? '-' }}</p>
            <p><strong>Condition:</strong> {{ ucfirst($item->condition) ?? '-' }}</p>
            <p><strong>Purchase Date:</strong> {{ $item->purchase_date?->format('Y-m-d') ?? '-' }}</p>
            <p><strong>PO Number:</strong> {{ $item->purchase_order_no ?? '-' }}</p>
            <p><strong>Warranty Expiration:</strong> {{ $item->warranty_expiration?->format('Y-m-d') ?? '-' }}</p>
            <p><strong>Remarks:</strong> {{ $item->remarks ?? '-' }}</p>
        </div>
    </div>
</body>
</html>
