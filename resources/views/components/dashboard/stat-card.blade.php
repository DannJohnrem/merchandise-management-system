@props(['label', 'value', 'icon' => 'squares-2x2', 'color' => 'indigo'])

@php
    $palette = [
        'indigo' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950 dark:text-indigo-300',
        'emerald' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-300',
        'blue' => 'bg-blue-50 text-blue-600 dark:bg-blue-950 dark:text-blue-300',
        'amber' => 'bg-amber-50 text-amber-600 dark:bg-amber-950 dark:text-amber-300',
        'slate' => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300',
    ];
@endphp

<div class="rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
    <div class="flex items-center justify-between">
        <div>
            <flux:text size="sm" class="text-neutral-500 dark:text-neutral-400">{{ $label }}</flux:text>
            <p class="mt-1 text-2xl font-semibold text-neutral-900 dark:text-neutral-100">{{ number_format($value) }}</p>
        </div>
        <div class="flex size-10 items-center justify-center rounded-lg {{ $palette[$color] ?? $palette['indigo'] }}">
            <flux:icon :name="$icon" class="size-5" />
        </div>
    </div>
</div>
