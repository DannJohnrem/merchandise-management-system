<div>
    {{-- ✅ Keep the label --}}
    <x-livewire-tables::tools.filter-label
        :$filter
        :$filterLayout
        :$tableName
        :$isTailwind
        :$isBootstrap4
        :$isBootstrap5
        :$isBootstrap
    />

    {{-- ✅ Styled container for Flux look --}}
    <div @class([
        'relative w-full' => $isTailwind,
        'inline' => $isBootstrap,
    ])>
        <select
            {!! $filter->getWireMethod('filterComponents.'.$filter->getKey()) !!}
            {{
                $filterInputAttributes->merge()
                    ->class([
                        // 🔹 Flux/Tailwind-inspired style
                        'block w-full rounded-lg border border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100
                         px-3 py-2 text-sm font-medium shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-1
                         transition duration-150 ease-in-out appearance-none cursor-pointer',
                    ])
                    ->except(['default-styling','default-colors'])
            }}
        >
            @foreach($filter->getOptions() as $key => $value)
                @if (is_iterable($value))
                    <optgroup label="{{ $key }}">
                        @foreach ($value as $optionKey => $optionValue)
                            <option value="{{ $optionKey }}">{{ $optionValue }}</option>
                        @endforeach
                    </optgroup>
                @else
                    <option value="{{ $key }}">{{ $value }}</option>
                @endif
            @endforeach
        </select>

        {{-- ▼ Dropdown arrow icon (Flux-style aesthetic) --}}
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 dark:text-gray-500">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </div>
</div>
