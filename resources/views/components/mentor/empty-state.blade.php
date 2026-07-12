@props(['icon' => 'inbox', 'title' => '', 'description' => ''])
<div {{ $attributes->merge(['class' => 'bg-white border border-dashed border-slate-300 rounded-2xl p-12 text-center']) }}>
    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center mx-auto mb-4 text-slate-300">
        <x-mentor.icon :name="$icon" class="w-6 h-6" />
    </div>
    <h4 class="text-sm font-bold text-slate-900 mb-1.5">{{ $title }}</h4>
    @if ($description)
        <p class="text-sm text-slate-500 mb-6 max-w-xs mx-auto">{{ $description }}</p>
    @endif
    {{ $slot }}
</div>
