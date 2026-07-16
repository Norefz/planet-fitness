@props([
    'label',
    'consumed' => 0,
    'target' => 0,
    'color' => 'emerald',
])

@php
    $pct = $target > 0 ? min(100, round(($consumed / $target) * 100)) : 0;

    $colorMap = [
        'amber'   => ['bar' => 'bg-amber-400',   'text' => 'text-amber-600'],
        'blue'    => ['bar' => 'bg-blue-400',    'text' => 'text-blue-600'],
        'rose'    => ['bar' => 'bg-rose-400',    'text' => 'text-rose-600'],
        'emerald' => ['bar' => 'bg-emerald-400', 'text' => 'text-emerald-600'],
    ];
    $c = $colorMap[$color] ?? $colorMap['emerald'];
@endphp

<div>
    <div class="mb-1.5 flex items-center justify-between">
        <span class="text-xs font-bold {{ $c['text'] }}">{{ $label }}</span>
        <span class="text-xs font-medium text-slate-400">
            {{ number_format($consumed) }}g <span class="text-slate-300">/</span> {{ number_format($target) }}g
        </span>
    </div>
    <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
        <div
            class="h-full rounded-full {{ $c['bar'] }} transition-all duration-700 ease-out"
            style="width: {{ $pct }}%"
        ></div>
    </div>
</div>
