@php
    $key = strtolower(trim($status ?? '')); // default to empty string if null
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
