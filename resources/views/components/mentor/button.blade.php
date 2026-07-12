@props([
    'variant' => 'primary',
    'size'    => 'default',
    'href'    => null,
    'type'    => 'button',
])
@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-xl font-semibold whitespace-nowrap transition-all duration-150 ease-out disabled:opacity-50 disabled:pointer-events-none focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2';

    $sizes = [
        'default' => 'text-sm px-4 py-2.5',
        'sm'      => 'text-xs px-3 py-2',
        'icon'    => 'w-9 h-9 p-0 shrink-0 text-sm',
    ];

    $variants = [
        'primary'      => 'bg-primary-500 text-white shadow-sm hover:bg-primary-600 hover:-translate-y-px hover:shadow-md focus-visible:ring-primary-400',
        'secondary'    => 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 hover:border-slate-300 focus-visible:ring-slate-300',
        'ghost'        => 'text-slate-500 hover:text-slate-900 hover:bg-slate-100 focus-visible:ring-slate-300',
        'danger'       => 'bg-white text-red-600 border border-red-200 hover:bg-red-50 hover:border-red-300 focus-visible:ring-red-300',
        'danger-solid' => 'bg-red-600 text-white shadow-sm hover:bg-red-700 hover:-translate-y-px hover:shadow-md focus-visible:ring-red-400',
    ];

    $classes = trim($base . ' ' . ($sizes[$size] ?? $sizes['default']) . ' ' . ($variants[$variant] ?? $variants['primary']));
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
