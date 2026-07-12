@props(['hover' => false, 'padding' => 'p-6'])
<div {{ $attributes->merge(['class' => 'bg-white border border-slate-200 rounded-2xl ' . $padding . ' ' . ($hover ? 'transition-all duration-150 hover:shadow-md hover:border-slate-300' : 'shadow-sm shadow-slate-200/50')]) }}>
    {{ $slot }}
</div>
