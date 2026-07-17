@props([
    'label'  => '',
    'value'  => '',
    'icon'   => null,
    'accent' => 'default',
    'sub'    => null,
])
@php
    $accents = [
        'default' => 'text-slate-900',
        'primary' => 'text-primary-600',
        'warning' => 'text-amber-600',
        'danger'  => 'text-red-500',
        'muted'   => 'text-slate-400',
    ];
@endphp
<div {{ $attributes->merge(['class' => 'bg-white border border-slate-200 rounded-2xl p-5 hover-lift']) }}>
    <div class="flex items-center justify-between mb-2.5">
        <div class="text-xs font-medium text-slate-500">{{ $label }}</div>
        @if ($icon)
            <div class="w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 shrink-0">
                <x-mentor.icon :name="$icon" class="w-3.5 h-3.5" />
            </div>
        @endif
    </div>
    <div class="text-[26px] leading-none font-bold tracking-tight {{ $accents[$accent] ?? $accents['default'] }}">{{ $value }}</div>
    @if ($sub)
        <div class="text-xs text-slate-400 mt-2">{{ $sub }}</div>
    @endif
</div>
