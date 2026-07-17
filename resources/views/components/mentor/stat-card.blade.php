@props([
    'label'  => '',
    'value'  => '',
    'icon'   => null,
    'accent' => 'default',
    'sub'    => null,
    'tilt'   => true,
])
@php
    $accents = [
        'default' => ['text' => 'text-slate-900', 'grad' => 'from-slate-400 to-slate-600'],
        'primary' => ['text' => 'text-primary-600', 'grad' => 'from-primary-400 to-primary-600'],
        'warning' => ['text' => 'text-amber-600', 'grad' => 'from-amber-400 to-amber-500'],
        'danger'  => ['text' => 'text-red-500', 'grad' => 'from-red-400 to-red-500'],
        'muted'   => ['text' => 'text-slate-400', 'grad' => 'from-slate-300 to-slate-400'],
    ];
    $a = $accents[$accent] ?? $accents['default'];
@endphp
<div {{ $attributes->merge(['class' => 'group relative bg-white border border-slate-200/80 rounded-2xl p-5 shadow-card-3d overflow-hidden transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-elevated hover:border-slate-300/70']) }}
     @if ($tilt) data-tilt data-tilt-strength="5" @endif>

    <div class="absolute -top-8 -right-8 w-24 h-24 rounded-full bg-gradient-to-br {{ $a['grad'] }} opacity-[0.07] blur-xl transition-opacity duration-300 group-hover:opacity-[0.16]"></div>

    <div class="relative flex items-center justify-between mb-3">
        <div class="text-xs font-semibold text-slate-500 tracking-wide">{{ $label }}</div>
        @if ($icon)
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br {{ $a['grad'] }} flex items-center justify-center text-white shrink-0 shadow-sm transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                <x-mentor.icon :name="$icon" class="w-3.5 h-3.5" />
            </div>
        @endif
    </div>

    <div class="relative text-[28px] leading-none font-extrabold tracking-tight tabular-nums {{ $a['text'] }}">{{ $value }}</div>

    @if ($sub)
        <div class="relative text-xs text-slate-400 mt-2.5">{{ $sub }}</div>
    @endif
</div>
