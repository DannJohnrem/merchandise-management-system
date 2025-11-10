@php
    use Illuminate\Support\Str;
@endphp

@if ($permissions->isEmpty())
    <span class="px-2 py-1 text-xs text-gray-500 dark:text-gray-400">No permissions</span>
@else
    <div class="flex flex-wrap gap-1">
        @foreach ($permissions as $name)
            @php
                $color = 'gray';
                $class = 'dark:bg-gray-600 dark:text-white';
                $lower = strtolower($name);

                switch (true) {
                    case Str::contains($lower, 'view'):
                        $color = 'cyan';
                        $class = 'dark:bg-gray-600 dark:text-white';
                        break;

                    case Str::contains($lower, 'create'):
                        $color = 'green';
                        $class = 'dark:bg-green-500 dark:text-black';
                        break;

                    case Str::contains($lower, 'edit'):
                        $color = 'amber';
                        $class = 'dark:bg-yellow-500 dark:text-black';
                        break;

                    case Str::contains($lower, 'delete'):
                        $color = 'red';
                        $class = 'dark:bg-red-500 dark:text-black';
                        break;

                    default:
                        $color = 'gray';
                        $class = 'dark:bg-gray-600 dark:text-white';
                        break;
                }
            @endphp

            <flux:badge color="{{ $color }}" class="{{ $class }}">
                {{ $name }}
            </flux:badge>
        @endforeach
    </div>
@endif
