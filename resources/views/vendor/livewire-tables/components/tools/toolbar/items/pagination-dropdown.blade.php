@aware([
    'tableName',
    'isTailwind',
    'isBootstrap',
    'isBootstrap4',
    'isBootstrap5',
    'localisationPath'
])

<div @class([
    'ml-0 ml-md-2' => $isBootstrap4,
    'ms-0 ms-md-2' => $isBootstrap5,
])>
    {{-- ✅ Flux UI Styled Dropdown --}}
    <flux:select
        wire:model.live="perPage"
        id="{{ $tableName }}-perPage"
        placeholder="Rows per page"
        class="w-40"
    >
        @foreach ($this->getPerPageAccepted() as $item)
            <flux:select.option
                value="{{ $item }}"
                wire:key="{{ $tableName }}-per-page-{{ $item }}"
            >
                {{ $item === -1 ? __($localisationPath.'All') : $item }}
            </flux:select.option>
        @endforeach
    </flux:select>
</div>
