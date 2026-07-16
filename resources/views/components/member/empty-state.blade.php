@props([
    'icon' => 'ti-mood-empty',
    'title' => '',
    'description' => '',
])

<div class="flex flex-col items-center py-10 text-center">
    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
        <i class="ti {{ $icon }} text-2xl" aria-hidden="true"></i>
    </div>

    @if ($title)
        <p class="mt-4 text-sm font-bold text-slate-700">{{ $title }}</p>
    @endif

    @if ($description)
        <p class="mt-1.5 max-w-xs text-sm text-slate-400">{{ $description }}</p>
    @endif

    {{ $slot }}

    @isset($action)
        <div class="mt-5">
            {{ $action }}
        </div>
    @endisset
</div>
