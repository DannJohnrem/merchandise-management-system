@php
    $key = strtolower(trim($status)); // status comes from DB
    $colors = [
        'available' => ['green', 'dark:bg-green-500 dark:text-white'],
        'in_use'    => ['blue', 'dark:bg-blue-500 dark:text-white'],
        'repair'    => ['yellow', 'dark:bg-yellow-500 dark:text-black'],
        'returned'  => ['purple', 'dark:bg-purple-500 dark:text-white'],
        'lost'      => ['red', 'dark:bg-red-500 dark:text-white'],
    ];

    $labels = [
        'available' => 'Available',
        'in_use'    => 'In Use',
        'repair'    => 'For Repair',
        'returned'  => 'Returned',
        'lost'      => 'Lost',
    ];

    [$color, $class] = $colors[$key] ?? ['slate', 'dark:bg-slate-500 dark:text-white'];
    $display = $labels[$key] ?? ucwords(str_replace(['-', '_'], ' ', $key));
@endphp

<flux:badge color="{{ $color }}" class="{{ $class }}">
    {{ $display }}
</flux:badge>
