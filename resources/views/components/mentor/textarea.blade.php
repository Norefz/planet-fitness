@props([
    'name'     => '',
    'label'    => '',
    'required' => false,
    'hint'     => null,
    'rows'     => 4,
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

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if($required) required @endif
        {{ $attributes->merge([
            'class' => 'w-full px-3.5 py-2.5 rounded-xl border text-sm placeholder-slate-400 bg-white transition-all duration-150 resize-none focus:outline-none focus:ring-4 '
                . ($hasError
                    ? 'border-red-300 focus:ring-red-500/10 focus:border-red-400'
                    : 'border-slate-200 focus:ring-primary-500/10 focus:border-primary-400')
        ]) }}
    >{{ $slot }}</textarea>

    @if ($hasError)
        <p class="flex items-center gap-1 text-xs text-red-600">
            <x-mentor.icon name="alert-circle" class="w-3.5 h-3.5 shrink-0" />
            {{ $errors->first($name) }}
        </p>
    @elseif ($hint)
        <p class="text-xs text-slate-400">{{ $hint }}</p>
    @endif
</div>
