<div class="relative mb-6 w-full">
    <flux:heading size="{{ $size ?? 'xl' }}" level="1">
        {{ $title }}
    </flux:heading>
    @if (!empty($subtitle))
        <flux:subheading size="lg" class="mb-6">
            {{ $subtitle }}
        </flux:subheading>
    @endif
    <flux:separator variant="subtle" />
</div>
