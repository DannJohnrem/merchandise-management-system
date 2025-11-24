@php
    $colors = [
        'super admin' => ['red', 'dark:bg-red-500 dark:text-white'],
        'admin' => ['amber', 'dark:bg-amber-500 dark:text-black'],
        'user' => ['sky', 'dark:bg-sky-500 dark:text-black'],
    ];
@endphp

@if ($roles->isEmpty())
    <span class="px-2 py-1 text-xs text-gray-500 dark:text-gray-400">No roles</span>
@else
    <div class="flex flex-wrap gap-1">
        @foreach ($roles as $name)
            @php
                $key = strtolower($name);
                [$color, $class] = $colors[$key] ?? ['slate', 'dark:bg-slate-500 dark:text-white'];
            @endphp

            <flux:badge color="{{ $color }}" class="{{ $class }}">
                {{ $name }}
            </flux:badge>
        @endforeach
    </div>
@endif
