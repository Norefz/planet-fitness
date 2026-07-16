@props(['log', 'deletable' => false])

<div
    class="group flex items-center gap-3 rounded-xl2 border border-slate-100 bg-white px-3 py-3 transition hover:border-slate-200 hover:shadow-sm sm:gap-4 sm:px-4"
>
    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full {{ $log->categoryColorClasses() }}">
        <i class="ti {{ $log->categoryIcon() }} text-lg" aria-hidden="true"></i>
    </div>

    <div class="min-w-0 flex-1">
        <p class="truncate text-sm font-semibold text-slate-800">{{ $log->food_name }}</p>
        <div class="mt-0.5 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-slate-400">
            <span class="font-medium text-slate-500">{{ $log->categoryLabel() }}</span>
            <span aria-hidden="true">&middot;</span>
            <span>{{ $log->carbs_g }}g karbo</span>
            <span aria-hidden="true">&middot;</span>
            <span>{{ $log->protein_g }}g protein</span>
            <span aria-hidden="true">&middot;</span>
            <span>{{ $log->fat_g }}g lemak</span>
        </div>
    </div>

    <div class="flex shrink-0 items-center gap-1 sm:gap-2">
        <span class="text-sm font-bold text-slate-800">
            {{ number_format($log->calories) }}<span class="ml-1 text-xs font-medium text-slate-400">kkal</span>
        </span>

        @if ($deletable)
            <form
                action="{{ route('member.log-nutrisi.destroy', $log) }}"
                method="POST"
                @submit="if (! confirm('Hapus ' + {!! \Illuminate\Support\Js::from($log->food_name) !!} + ' dari log nutrisi?')) $event.preventDefault()"
            >
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="flex h-8 w-8 items-center justify-center rounded-full text-slate-300 opacity-0 transition hover:bg-rose-50 hover:text-rose-500 focus-visible:opacity-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-400 group-hover:opacity-100"
                    aria-label="Hapus {{ $log->food_name }} dari log"
                >
                    <i class="ti ti-trash text-base" aria-hidden="true"></i>
                </button>
            </form>
        @endif
    </div>
</div>
