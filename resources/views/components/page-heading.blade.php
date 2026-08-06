<div class="relative mb-6 w-full">
    <div class="flex items-start justify-between gap-4">
        <div>
            <flux:heading size="{{ $size ?? 'xl' }}" level="1">
                {{ $title }}
            </flux:heading>
            @if (!empty($subtitle))
                <flux:subheading size="lg">
                    {{ $subtitle }}
                </flux:subheading>
            @endif
        </div>

        @if (isset($actions))
            <div class="flex items-center gap-2">
                {{ $actions }}
            </div>
        @endif
    </div>

    <flux:separator variant="subtle" class="mt-4" />
</div>
