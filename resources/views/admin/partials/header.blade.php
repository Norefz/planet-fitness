{{-- resources/views/admin/partials/header.blade.php --}}
<header class="h-16 bg-white border-b border-slate-200 flex items-center px-7 gap-4 sticky top-0 z-40">

  {{-- Page title (dikirim dari tiap view) --}}
  <div class="flex-1 text-[16px] font-bold text-slate-900">
    @yield('page_title', 'Dashboard')
    @hasSection('page_subtitle')
      <span class="font-normal text-slate-400 text-sm ml-1.5">/ @yield('page_subtitle')</span>
    @endif
  </div>

  {{-- Search --}}
  <form method="GET" action="{{ route('admin.search') }}" class="flex items-center gap-2 bg-slate-100 border border-slate-200 rounded-lg px-3.5 py-2 w-60">
    <svg class="w-[15px] h-[15px] text-slate-400 flex-shrink-0" fill="none" stroke="currentColor"
         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
      <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
    </svg>
    <input name="q" type="search" value="{{ request()->routeIs('admin.search') ? request('q') : '' }}" placeholder="Cari member, mentor, program..."
           class="border-none bg-transparent text-[13px] text-slate-700 outline-none w-full
                  placeholder:text-slate-400" />
  </form>

  {{-- Actions --}}
  <div class="flex items-center gap-1.5">

    {{-- Notification bell --}}
    <div x-data="{ open: false }" class="relative">
    <button type="button" @click="open = !open" class="w-9 h-9 rounded-lg border border-slate-200 bg-white flex items-center justify-center
                   text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-all duration-200
                   relative cursor-pointer">
      <svg class="w-[17px] h-[17px]" fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
      </svg>
      @if($notifications->isNotEmpty())
      <span class="absolute top-[7px] right-[7px] w-[7px] h-[7px] bg-red-500 rounded-full
                   border-[1.5px] border-white"></span>
      @endif
    </button>

    <div x-show="open" @click.outside="open = false" x-transition
         class="absolute right-0 top-11 w-80 bg-white rounded-xl border border-slate-200 shadow-xl overflow-hidden z-50"
         style="display: none;">
      <div class="px-4 py-3 border-b border-slate-100 font-bold text-sm text-slate-800">Notifikasi</div>
      @forelse($notifications as $notification)
        <a href="{{ $notification['url'] }}" class="block px-4 py-3 border-b border-slate-100 last:border-0 hover:bg-slate-50 no-underline">
          <div class="text-sm font-semibold text-slate-700">{{ $notification['title'] }}</div>
          <div class="text-xs text-slate-500 mt-0.5">{{ $notification['message'] }}</div>
          <div class="text-[11px] text-slate-400 mt-1">{{ $notification['time']?->diffForHumans() }}</div>
        </a>
      @empty
        <div class="px-4 py-8 text-center text-sm text-slate-400">Tidak ada notifikasi baru.</div>
      @endforelse
    </div>
    </div>

    {{-- Settings --}}
    <a href="{{ route('admin.config') }}"
       class="w-9 h-9 rounded-lg border border-slate-200 bg-white flex items-center justify-center
              text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-all duration-200
              no-underline">
      <svg class="w-[17px] h-[17px]" fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="3"/>
        <path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/>
      </svg>
    </a>

  </div>

</header>
