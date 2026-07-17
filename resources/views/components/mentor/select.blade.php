@props([
    'name'     => '',
    'label'    => '',
    'required' => false,
    'hint'     => null,
])
@php
    $hasError = $errors->has($name);
@endphp
<div class="flex flex-col gap-1.5">
    @if ($label)
        <label for="{{ $name }}" class="text-xs font-semibold text-slate-700">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif

    <div class="relative">
        <select
            id="{{ $name }}"
            name="{{ $name }}"
            @if($required) required @endif
            {{ $attributes->merge([
                'class' => 'w-full pl-3.5 pr-9 py-2.5 rounded-xl border text-sm bg-white appearance-none transition-all duration-200 shadow-sm focus:outline-none focus:ring-4 hover:border-slate-300 '
                    . ($hasError
                        ? 'border-red-300 focus:ring-red-500/10 focus:border-red-400'
                        : 'border-slate-200 focus:ring-primary-500/12 focus:border-primary-400')
            ]) }}
        >{{ $slot }}</select>
        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
            <x-mentor.icon name="chevron-down" class="w-4 h-4" />
        </div>
    </div>

    @if ($hasError)
        <p class="flex items-center gap-1 text-xs text-red-600">
            <x-mentor.icon name="alert-circle" class="w-3.5 h-3.5 shrink-0" />
            {{ $errors->first($name) }}
        </p>
    @elseif ($hint)
        <p class="text-xs text-slate-400">{{ $hint }}</p>
    @endif
</div>
