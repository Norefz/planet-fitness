@props([
    'name'     => '',
    'label'    => '',
    'type'     => 'text',
    'required' => false,
    'hint'     => null,
    'icon'     => null,
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
        @if ($icon)
            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                <x-mentor.icon :name="$icon" class="w-4 h-4" />
            </div>
        @endif

        <input
            type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            @if($required) required @endif
            {{ $attributes->merge([
                'class' => 'w-full py-2.5 rounded-xl border text-sm placeholder-slate-400 bg-white transition-all duration-200 shadow-sm focus:outline-none focus:ring-4 hover:border-slate-300 '
                    . ($icon ? 'pl-10 pr-4' : 'px-3.5')
                    . ' ' . ($hasError
                        ? 'border-red-300 focus:ring-red-500/10 focus:border-red-400'
                        : 'border-slate-200 focus:ring-primary-500/12 focus:border-primary-400 focus:shadow-[0_4px_16px_-4px_rgba(29,158,117,0.25)]')
            ]) }}
        />
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
