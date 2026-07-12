@props(['name' => '?', 'size' => 'md', 'tone' => 'primary'])
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
        'primary' => 'bg-primary-50 text-primary-700',
        'neutral' => 'bg-slate-100 text-slate-600',
        'amber'   => 'bg-amber-50 text-amber-700',
    ];
@endphp
<div {{ $attributes->merge(['class' => 'rounded-full flex items-center justify-center font-bold shrink-0 ' . ($sizes[$size] ?? $sizes['md']) . ' ' . ($tones[$tone] ?? $tones['primary'])]) }}>
    {{ $initials }}
</div>
