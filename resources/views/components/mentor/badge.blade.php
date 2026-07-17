@props(['variant' => 'neutral', 'dot' => false])
@php
    $variants = [
        'success' => ['bg' => 'bg-primary-50 text-primary-700', 'dot' => 'bg-primary-500'],
        'warning' => ['bg' => 'bg-amber-50 text-amber-700', 'dot' => 'bg-amber-500'],
        'danger'  => ['bg' => 'bg-red-50 text-red-600', 'dot' => 'bg-red-500'],
        'neutral' => ['bg' => 'bg-slate-100 text-slate-600', 'dot' => 'bg-slate-400'],
        'info'    => ['bg' => 'bg-blue-50 text-blue-700', 'dot' => 'bg-blue-500'],
    ];
    $v = $variants[$variant] ?? $variants['neutral'];
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-full leading-none ' . $v['bg']]) }}>
    @if ($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $v['dot'] }}"></span>
    @endif
    {{ $slot }}
</span>
