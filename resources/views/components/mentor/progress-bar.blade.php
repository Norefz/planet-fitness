@props(['value' => 0, 'height' => 'h-2', 'showLabel' => false])
@php
    $value = max(0, min(100, (float) $value));
    $color = $value >= 70 ? 'bg-primary-500' : ($value >= 30 ? 'bg-amber-400' : 'bg-red-400');
@endphp
<div {{ $attributes->class(['flex items-center gap-2.5' => $showLabel]) }}>
    <div class="w-full {{ $height }} bg-slate-100 rounded-full overflow-hidden">
        <div class="{{ $color }} {{ $height }} rounded-full transition-all duration-500 ease-out" style="width: {{ $value }}%"></div>
    </div>
    @if ($showLabel)
        <span class="text-xs font-bold text-slate-600 tabular-nums w-9 text-right shrink-0">{{ rtrim(rtrim(number_format($value, 1), '0'), '.') }}%</span>
    @endif
</div>
