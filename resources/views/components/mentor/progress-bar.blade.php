@props(['value' => 0, 'height' => 'h-2', 'showLabel' => false])
@php
    $value = max(0, min(100, (float) $value));
    $grad = $value >= 70 ? 'from-primary-400 to-primary-600' : ($value >= 30 ? 'from-amber-300 to-amber-500' : 'from-red-300 to-red-500');
@endphp
<div {{ $attributes->class(['flex items-center gap-2.5' => $showLabel]) }}>
    <div class="w-full {{ $height }} bg-slate-100 rounded-full overflow-hidden">
        <div class="bg-gradient-to-r {{ $grad }} {{ $height }} rounded-full transition-all duration-700 ease-out shadow-[0_0_8px_-1px_rgba(29,158,117,0.5)]" style="width: {{ $value }}%"></div>
    </div>
    @if ($showLabel)
        <span class="text-xs font-bold text-slate-600 tabular-nums w-9 text-right shrink-0">{{ rtrim(rtrim(number_format($value, 1), '0'), '.') }}%</span>
    @endif
</div>
