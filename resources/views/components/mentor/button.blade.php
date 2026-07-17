@props([
    'variant'  => 'primary',
    'size'     => 'default',
    'href'     => null,
    'type'     => 'button',
    'magnetic' => false,
])
@php
    $base = 'relative inline-flex items-center justify-center gap-2 rounded-xl font-semibold whitespace-nowrap transition-all duration-200 ease-out disabled:opacity-50 disabled:pointer-events-none focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 overflow-hidden select-none';

    $sizes = [
        'default' => 'text-sm px-5 py-2.5',
        'sm'      => 'text-xs px-3.5 py-2',
        'lg'      => 'text-[15px] px-6 py-3.5',
        'icon'    => 'w-9 h-9 p-0 shrink-0 text-sm',
    ];

    $variants = [
        'primary'      => 'bg-gradient-to-b from-primary-400 to-primary-600 text-white shadow-[0_1px_0_0_rgba(255,255,255,0.25)_inset,0_8px_20px_-6px_rgba(29,158,117,0.55)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.3)_inset,0_12px_28px_-8px_rgba(29,158,117,0.65)] hover:-translate-y-0.5 focus-visible:ring-primary-400 btn-shine',
        'secondary'    => 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 hover:border-slate-300 hover:-translate-y-0.5 hover:shadow-sm focus-visible:ring-slate-300',
        'ghost'        => 'text-slate-500 hover:text-slate-900 hover:bg-slate-100 focus-visible:ring-slate-300',
        'dark'         => 'bg-gradient-to-b from-ink-800 to-ink-900 text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 focus-visible:ring-ink-700 btn-shine',
        'danger'       => 'bg-white text-red-600 border border-red-200 hover:bg-red-50 hover:border-red-300 focus-visible:ring-red-300',
        'danger-solid' => 'bg-gradient-to-b from-red-500 to-red-600 text-white shadow-[0_1px_0_0_rgba(255,255,255,0.25)_inset,0_8px_18px_-6px_rgba(220,38,38,0.5)] hover:shadow-[0_1px_0_0_rgba(255,255,255,0.3)_inset,0_10px_24px_-6px_rgba(220,38,38,0.6)] hover:-translate-y-0.5 focus-visible:ring-red-400',
    ];

    $classes = trim($base . ' ' . ($sizes[$size] ?? $sizes['default']) . ' ' . ($variants[$variant] ?? $variants['primary']));
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} @if ($magnetic) data-magnetic @endif>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} @if ($magnetic) data-magnetic @endif>{{ $slot }}</button>
@endif
