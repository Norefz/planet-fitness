@props(['name' => '?', 'size' => 'md', 'tone' => 'primary', 'ring' => false])
@php
    $words = preg_split('/\s+/', trim($name));
    $initials = implode('', array_map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)), array_slice($words, 0, 2))) ?: '?';

    $sizes = [
        'sm' => 'w-8 h-8 text-[11px]',
        'md' => 'w-9 h-9 text-xs',
        'lg' => 'w-11 h-11 text-sm',
        'xl' => 'w-20 h-20 text-2xl',
    ];

    $tones = [
        'primary' => 'bg-gradient-to-br from-primary-400 to-primary-600 text-white',
        'neutral' => 'bg-gradient-to-br from-slate-200 to-slate-300 text-slate-700',
        'amber'   => 'bg-gradient-to-br from-amber-400 to-amber-500 text-white',
    ];

    $ringClass = $ring ? ' ring-4 ring-white shadow-elevated' : ' shadow-sm';
@endphp
<div {{ $attributes->merge(['class' => 'rounded-full flex items-center justify-center font-bold shrink-0 ' . ($sizes[$size] ?? $sizes['md']) . ' ' . ($tones[$tone] ?? $tones['primary']) . $ringClass]) }}>
    {{ $initials }}
</div>
