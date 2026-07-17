@props(['icon' => 'inbox', 'title' => '', 'description' => ''])
<div {{ $attributes->merge(['class' => 'relative bg-white border-2 border-dashed border-slate-200 rounded-3xl p-12 text-center overflow-hidden']) }}>
    <div class="absolute inset-0 mesh-light pointer-events-none"></div>
    <div class="relative w-14 h-14 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center mx-auto mb-4 text-slate-400 shadow-inner">
        <x-mentor.icon :name="$icon" class="w-6 h-6" />
    </div>
    <h4 class="relative text-sm font-bold text-slate-900 mb-1.5">{{ $title }}</h4>
    @if ($description)
        <p class="relative text-sm text-slate-500 mb-6 max-w-xs mx-auto">{{ $description }}</p>
    @endif
    <div class="relative">{{ $slot }}</div>
</div>
