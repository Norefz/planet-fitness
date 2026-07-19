@extends('admin.layouts.app')

@section('title', 'Manajemen Member')
@section('page_title', 'Manajemen Member')
@section('page_subtitle', 'Daftar Semua Member')

@section('content')

{{-- ══════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════ --}}
<div class="flex items-center justify-between mb-6">
  <div>
    <h1 class="text-[22px] font-extrabold tracking-tight text-slate-900">Manajemen Member</h1>
    <p class="text-[13px] text-slate-500 mt-0.5">
      Total {{ number_format($stats['total']) }} member terdaftar · {{ number_format($stats['active']) }} aktif · {{ number_format($stats['inactive']) }} tidak aktif
    </p>
  </div>
</div>

{{-- ══════════════════════════════════════════
     STAT CARDS
══════════════════════════════════════════ --}}
<div class="grid grid-cols-4 gap-4 mb-6">

  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5 shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#1d9e75;">
    <div class="w-10 h-10 rounded-[10px] bg-primary-light flex items-center justify-center">
      <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
      </svg>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ number_format($stats['total']) }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Total Member</div>
    </div>
  </div>

  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5 shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#3b82f6;">
    <div class="w-10 h-10 rounded-[10px] bg-blue-50 flex items-center justify-center">
      <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
      </svg>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ number_format($stats['active']) }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Member Aktif</div>
    </div>
  </div>

  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5 shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#8b5cf6;">
    <div class="w-10 h-10 rounded-[10px] bg-violet-50 flex items-center justify-center">
      <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
      </svg>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ number_format($stats['premium']) }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Member Premium</div>
    </div>
  </div>

  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5 shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#94a3b8;">
    <div class="w-10 h-10 rounded-[10px] bg-slate-100 flex items-center justify-center">
      <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
      </svg>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ number_format($stats['inactive']) }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Tidak Aktif</div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════
     FILTER + SEARCH + TABLE
══════════════════════════════════════════ --}}
<div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">

  <form method="GET" action="{{ route('admin.members') }}" class="flex items-center gap-3 px-5 py-4 border-b border-slate-100 flex-wrap">

    <div class="flex items-center gap-1 bg-slate-100 rounded-xl p-1">
      @foreach(['all' => 'Semua', 'active' => 'Aktif', 'inactive' => 'Tidak Aktif'] as $val => $label)
        <a href="{{ route('admin.members', array_filter(['status' => $val, 'subscription' => $subscription, 'sort' => $sort, 'q' => $q])) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ $status === $val ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
          {{ $label }}
        </a>
      @endforeach
    </div>

    <input type="hidden" name="status" value="{{ $status }}" />

    <select name="subscription" onchange="this.form.submit()"
            class="px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-600 cursor-pointer">
      <option value="all" {{ $subscription === 'all' ? 'selected' : '' }}>Semua Langganan</option>
      <option value="free" {{ $subscription === 'free' ? 'selected' : '' }}>Free</option>
      <option value="premium" {{ $subscription === 'premium' ? 'selected' : '' }}>Premium</option>
    </select>

    <select name="sort" onchange="this.form.submit()"
            class="px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-600 cursor-pointer">
      <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Urutkan: Terbaru</option>
      <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Urutkan: A–Z</option>
      <option value="most_programs" {{ $sort === 'most_programs' ? 'selected' : '' }}>Urutkan: Program Terbanyak</option>
    </select>

    <div class="flex items-center gap-2 bg-slate-100 border border-slate-200 rounded-lg px-3.5 py-2 w-64 ml-auto">
      <svg class="w-[15px] h-[15px] text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama, email, telepon..."
             class="border-none bg-transparent text-[13px] text-slate-700 outline-none w-full placeholder:text-slate-400" />
    </div>
  </form>

  <table class="w-full border-collapse">
    <thead>
      <tr class="bg-slate-50">
        <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Member</th>
        <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Langganan</th>
        <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Program Diikuti</th>
        <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Status</th>
        <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Bergabung</th>
        <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($members as $member)
        @php $isActive = $member->user?->is_active ?? false; @endphp
        <tr class="border-b border-slate-100 last:border-b-0 hover:bg-slate-50 transition-colors">
          <td class="px-4 py-3">
            <div class="flex items-center gap-3">
              @if($member->profile_photo_url)
                <img src="{{ $member->profile_photo_url }}" alt="{{ $member->full_name }}"
                     class="w-9 h-9 rounded-full object-cover flex-shrink-0" />
              @else
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                     style="background: linear-gradient(135deg, #1d9e75, #0f6e56);">
                  {{ $member->initials() }}
                </div>
              @endif
              <div class="min-w-0">
                <a href="{{ route('admin.members.show', $member) }}" class="text-[13px] font-semibold text-slate-900 truncate hover:underline no-underline block">
                  {{ $member->full_name }}
                </a>
                <div class="text-[11px] text-slate-400 truncate">{{ $member->user?->email }}</div>
              </div>
            </div>
          </td>
          <td class="px-4 py-3">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold
                         {{ $member->subscription_type === 'premium' ? 'bg-violet-50 text-violet-700' : 'bg-primary-light text-primary-dark' }}">
              {{ ucfirst($member->subscription_type) }}
            </span>
          </td>
          <td class="px-4 py-3 text-[12px] text-slate-500">{{ $member->enrollments_count }} program</td>
          <td class="px-4 py-3">
            @if($isActive)
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-primary-light text-primary-dark">
                <svg fill="currentColor" viewBox="0 0 24 24" class="w-2 h-2"><circle cx="12" cy="12" r="5"/></svg> Aktif
              </span>
            @else
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500">
                <svg fill="currentColor" viewBox="0 0 24 24" class="w-2 h-2"><circle cx="12" cy="12" r="5"/></svg> Tidak aktif
              </span>
            @endif
          </td>
          <td class="px-4 py-3 text-[12px] text-slate-400">{{ $member->created_at->diffForHumans() }}</td>
          <td class="px-4 py-3">
            <div class="flex items-center gap-1.5">
              <a href="{{ route('admin.members.show', $member) }}" title="Lihat profil"
                 class="w-7 h-7 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-all duration-200 no-underline">
                <svg class="w-[13px] h-[13px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </a>

              @if($isActive)
                <form method="POST" action="{{ route('admin.members.toggle-active', $member) }}" onsubmit="return confirm('Nonaktifkan member ini?');">
                  @csrf @method('PATCH')
                  <button type="submit" title="Nonaktifkan"
                          class="w-7 h-7 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-red-500 hover:bg-red-50 transition-all duration-200 cursor-pointer">
                    <svg class="w-[13px] h-[13px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                  </button>
                </form>
              @else
                <form method="POST" action="{{ route('admin.members.toggle-active', $member) }}">
                  @csrf @method('PATCH')
                  <button type="submit" title="Aktifkan kembali"
                          class="w-7 h-7 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-primary hover:bg-primary-light transition-all duration-200 cursor-pointer">
                    <svg class="w-[13px] h-[13px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                  </button>
                </form>
              @endif
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="px-4 py-10 text-center text-[13px] text-slate-400">
            Tidak ada member yang cocok dengan pencarian/filter ini.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  @if($members->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">
      {{ $members->links() }}
    </div>
  @endif
</div>

@endsection
