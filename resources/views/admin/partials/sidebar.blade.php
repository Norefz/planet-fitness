{{-- resources/views/admin/partials/sidebar.blade.php --}}
<aside class="w-60 bg-navy fixed top-0 left-0 min-h-screen flex flex-col z-50 flex-shrink-0">

  {{-- Logo --}}
  <a href="{{ route('admin.dashboard') }}"
     class="flex items-center gap-2.5 px-5 py-5 border-b border-white/[.07] no-underline">
    <div class="w-[34px] h-[34px] rounded-full bg-primary flex items-center justify-center
                flex-shrink-0 shadow-[0_0_0_4px_rgba(29,158,117,.2)]">
      {{-- Dumbbell icon --}}
      <svg class="w-[17px] h-[17px] text-white" fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M6 5v14M18 5v14M6 8H2M6 16H2M22 8h-4M22 16h-4M6 12h12"/>
      </svg>
    </div>
    <div>
      <div class="text-[15px] font-bold text-white tracking-tight">Planet Fitness</div>
      <div class="text-[10px] font-bold text-primary uppercase tracking-[1.5px]">Super Admin</div>
    </div>
  </a>

  {{-- Nav --}}
  <nav class="flex-1 px-2.5 py-2 flex flex-col gap-0.5 overflow-y-auto">

    <div class="text-[10px] font-bold text-white/30 uppercase tracking-[1.5px] px-3 py-3 pt-5">
      Menu Utama
    </div>

    @php
      $nav = [
        ['route' => 'admin.dashboard',  'label' => 'Dashboard',         'icon' => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>',                                                                   'badge' => null,                            'badge_class' => ''],
        ['route' => 'admin.members',    'label' => 'Manajemen Member',  'icon' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',                                                                  'badge' => number_format($stats['total_members'] ?? 0), 'badge_class' => 'bg-primary'],
        ['route' => 'admin.mentors',    'label' => 'Manajemen Mentor',  'icon' => '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>',                                                                                                                                                   'badge' => $stats['pending_mentors'] ?? 0,  'badge_class' => 'bg-red-500'],
        ['route' => 'admin.programs',   'label' => 'Program Latihan',   'icon' => '<path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/>',                                                                                                             'badge' => null,                            'badge_class' => ''],
        ['route' => 'admin.bookings',   'label' => 'Booking Konsultasi','icon' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',                                                                  'badge' => null,                            'badge_class' => ''],
      ];
      $sys = [
        ['route' => 'admin.reports',    'label' => 'Laporan & Analitik','icon' => '<path d="M18 20V10M12 20V4M6 20v-6"/>'],
        ['route' => 'admin.config',     'label' => 'Konfigurasi Sistem','icon' => '<circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/>'],
      ];

      if (auth('admin')->user()?->superAdmin?->is_head) {
        array_splice($sys, 1, 0, [[
          'route' => 'admin.logs',
          'label' => 'Log Admin',
          'icon' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        ]]);
      }
    @endphp

    @foreach($nav as $item)
      @php $isActive = request()->routeIs($item['route']); @endphp
      <a href="{{ route($item['route']) }}"
         class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[13.5px] font-medium
                transition-all duration-200 no-underline
                {{ $isActive ? 'nav-item-active' : 'text-white/55 hover:text-white hover:bg-white/[.07]' }}">
        <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          {!! $item['icon'] !!}
        </svg>
        {{ $item['label'] }}
        @if($item['badge'])
          <span class="ml-auto text-[10px] font-bold text-white px-1.5 py-0.5 rounded-full
                       min-w-[18px] text-center {{ $item['badge_class'] }}">
            {{ $item['badge'] }}
          </span>
        @endif
      </a>
    @endforeach

    <div class="h-px bg-white/[.07] my-2 mx-2.5"></div>
    <div class="text-[10px] font-bold text-white/30 uppercase tracking-[1.5px] px-3 pb-2">Sistem</div>

    @foreach($sys as $item)
      @php $isActive = request()->routeIs($item['route']); @endphp
      <a href="{{ route($item['route']) }}"
         class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-[13.5px] font-medium
                transition-all duration-200 no-underline
                {{ $isActive ? 'nav-item-active' : 'text-white/55 hover:text-white hover:bg-white/[.07]' }}">
        <svg class="w-[17px] h-[17px] flex-shrink-0" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          {!! $item['icon'] !!}
        </svg>
        {{ $item['label'] }}
      </a>
    @endforeach

  </nav>

  {{-- Admin user footer --}}
  <div class="p-2.5 border-t border-white/[.07]">
    <div x-data="{ open: false }" class="relative">
      <button @click="open = !open"
              class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-lg
                     hover:bg-white/[.07] transition-all duration-200 cursor-pointer">
        @if(auth('admin')->user()->avatar)
          <img src="{{ auth('admin')->user()->avatar }}"
               class="w-8 h-8 rounded-full object-cover flex-shrink-0" />
        @else
          <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary to-primary-dark
                      flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
            {{ strtoupper(substr(auth('admin')->user()->full_name, 0, 2)) }}
          </div>
        @endif
        <div class="flex-1 min-w-0 text-left">
          <div class="text-[13px] font-semibold text-white truncate">
            {{ auth('admin')->user()->full_name }}
          </div>
          <div class="text-[11px] text-white/40 truncate">
            {{ auth('admin')->user()->email }}
          </div>
        </div>
        <svg class="w-[15px] h-[15px] text-white/30 flex-shrink-0" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <line x1="17" y1="7" x2="7" y2="17"/><polyline points="7 7 7 17 17 17"/>
        </svg>
      </button>

      {{-- Dropdown --}}
      <div x-show="open" @click.outside="open = false"
           x-transition:enter="transition ease-out duration-150"
           x-transition:enter-start="opacity-0 translate-y-1"
           x-transition:enter-end="opacity-100 translate-y-0"
           class="absolute bottom-full left-0 right-0 mb-1 bg-white rounded-xl
                  border border-slate-200 shadow-lg overflow-hidden"
           style="display:none;">
        <form method="POST" action="{{ route('admin.logout') }}">
          @csrf
          <button type="submit"
                  class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600
                         hover:bg-red-50 transition-colors duration-150 cursor-pointer">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M14 8v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2v-2"/>
              <path d="M9 12h12l-3-3m0 6l3-3"/>
            </svg>
            Keluar dari Admin
          </button>
        </form>
      </div>
    </div>
  </div>

</aside>

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
