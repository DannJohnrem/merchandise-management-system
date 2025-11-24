<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Leasing Info - {{ config('app.name') }}</title>

    <!-- Tailwind / App CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Optional: fluxAppearance if you want Flux styling -->
    @fluxAppearance
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800 flex justify-center items-start p-6">

    <div class="max-w-lg w-full bg-white shadow-lg rounded-lg p-6 mt-10 dark:bg-zinc-900 dark:text-white">
        <h1 class="text-2xl font-bold mb-4">IT Leasing Information</h1>

        <div class="space-y-3">
            <p><strong>Category:</strong> {{ ucfirst($item->category) }}</p>
            <p><strong>Serial Number:</strong> {{ $item->serial_number }}</p>
            <p><strong>Brand:</strong> {{ $item->brand ?? '-' }}</p>
            <p><strong>Model:</strong> {{ $item->model ?? '-' }}</p>

            @if ($item->cost)
                <p><strong>Cost:</strong> ₱{{ number_format($item->cost, 2) }}</p>
            @endif

            @if ($item->remarks)
                <p><strong>Remarks:</strong> {{ $item->remarks }}</p>
            @endif
        </div>
    </div>

</body>
</html>
