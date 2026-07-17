@props(['hover' => false, 'padding' => 'p-6', 'variant' => 'light', 'tilt' => false])
@php
    $variants = [
        'light' => 'bg-white border border-slate-200/80 shadow-card-3d',
        'dark'  => 'bg-ink-900 border border-white/10 text-white shadow-elevated',
        'glass' => 'glass shadow-card-3d',
    ];
    $base = 'relative rounded-3xl ' . $padding . ' ' . ($variants[$variant] ?? $variants['light']);
    $interactive = $hover
        ? ' transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-elevated ' . ($variant === 'dark' ? 'hover:border-white/20' : 'hover:border-slate-300/80')
        : '';
@endphp
<div {{ $attributes->merge(['class' => trim($base . $interactive)]) }} @if ($tilt) data-tilt data-tilt-strength="5" @endif>
    {{ $slot }}
</div>
