@php
    $key = strtolower(trim($action ?? ''));

    $colors = [
        'created'  => ['green',  'dark:bg-green-500 dark:text-white'],
        'updated'  => ['blue',   'dark:bg-blue-500 dark:text-white'],
        'deleted'  => ['red',    'dark:bg-red-500 dark:text-white'],
        'restored' => ['purple', 'dark:bg-purple-500 dark:text-white'],
        'login'    => ['indigo', 'dark:bg-indigo-500 dark:text-white'],
        'logout'   => ['gray',   'dark:bg-gray-500 dark:text-white'],
    ];

    $labels = [
        'created'  => 'Created',
        'updated'  => 'Updated',
        'deleted'  => 'Deleted',
        'restored' => 'Restored',
        'login'    => 'Login',
        'logout'   => 'Logout',
    ];

    [$color, $class] = $colors[$key] ?? ['slate', 'dark:bg-slate-500 dark:text-white'];
    $display = $labels[$key] ?? ucfirst($action ?? '—');
@endphp

<flux:badge color="{{ $color }}" class="{{ $class }}">
    {{ $display }}
</flux:badge>
