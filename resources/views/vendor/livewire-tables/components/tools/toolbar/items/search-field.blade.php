@aware(['isTailwind', 'isBootstrap'])

<div class="flex items-center gap-2 w-full max-w-xs">
    {{-- 🔍 Search Input --}}
    <flux:input
        type="text"
        icon="search"
        placeholder="{{ $this->getSearchPlaceholder() ?? 'Search...' }}"
        {{ $attributes->merge([
            'wire:model'.$this->getSearchOptions() => 'search'
        ]) }}
        class="w-full"
    />

    {{-- ❌ Clear Button --}}
    @if($this->hasSearch)
        <flux:button
            wire:click="clearSearch"
            icon="x"
            variant="ghost"
            size="sm"
            class="shrink-0"
            title="Clear search"
        />
    @endif
</div>
