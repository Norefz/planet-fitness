@props(['variant' => 'neutral'])
@php
    $variants = [
        'success' => 'bg-primary-50 text-primary-700',
        'warning' => 'bg-amber-50 text-amber-700',
        'danger'  => 'bg-red-50 text-red-600',
        'neutral' => 'bg-slate-100 text-slate-600',
        'info'    => 'bg-blue-50 text-blue-700',
    ];
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full leading-none ' . ($variants[$variant] ?? $variants['neutral'])]) }}>
    {{ $slot }}
</span>
