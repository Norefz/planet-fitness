@extends('admin.layouts.app')

@section('title', $logTitle)
@section('page_title', $logTitle)
@section('page_subtitle', $logSubtitle)

@section('content')

@php
    // Peta gaya visual (ikon + warna) berdasarkan kata kunci pada kolom `action`.
    // Urutan penting: kata kunci yang lebih spesifik dicek lebih dulu.
    $styleFor = function (string $action) {
        $map = [
            'delete'   => ['tag' => 'DELETE',  'bg' => 'bg-red-50',     'fg' => 'text-red-500',
                           'icon' => '<polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6m4-6v6"/>'],
            'reject'   => ['tag' => 'REJECT',  'bg' => 'bg-amber-50',   'fg' => 'text-amber-600',
                           'icon' => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>'],
            'suspend'  => ['tag' => 'SUSPEND', 'bg' => 'bg-red-50',     'fg' => 'text-red-500',
                           'icon' => '<circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>'],
            'fail'     => ['tag' => 'FAILED',  'bg' => 'bg-red-50',     'fg' => 'text-red-500',
                           'icon' => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>'],
            'verify'   => ['tag' => 'VERIFY',  'bg' => 'bg-amber-50',   'fg' => 'text-amber-600',
                           'icon' => '<polyline points="20 6 9 17 4 12"/>'],
            'confirm'  => ['tag' => 'CONFIRM', 'bg' => 'bg-primary-light', 'fg' => 'text-primary',
                           'icon' => '<polyline points="20 6 9 17 4 12"/>'],
            'activate' => ['tag' => 'ACTIVATE','bg' => 'bg-primary-light', 'fg' => 'text-primary',
                           'icon' => '<polyline points="20 6 9 17 4 12"/>'],
            'publish'  => ['tag' => 'PUBLISH', 'bg' => 'bg-primary-light', 'fg' => 'text-primary',
                           'icon' => '<polyline points="20 6 9 17 4 12"/>'],
            'creat'    => ['tag' => 'CREATE',  'bg' => 'bg-primary-light', 'fg' => 'text-primary',
                           'icon' => '<line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>'],
            'register' => ['tag' => 'CREATE',  'bg' => 'bg-primary-light', 'fg' => 'text-primary',
                           'icon' => '<line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>'],
            'login'    => ['tag' => 'LOGIN',   'bg' => 'bg-blue-50',    'fg' => 'text-blue-500',
                           'icon' => '<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>'],
            'logout'   => ['tag' => 'LOGOUT',  'bg' => 'bg-slate-100',  'fg' => 'text-slate-500',
                           'icon' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>'],
            'update'   => ['tag' => 'UPDATE',  'bg' => 'bg-blue-50',    'fg' => 'text-blue-500',
                           'icon' => '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>'],
            'chang'    => ['tag' => 'UPDATE',  'bg' => 'bg-blue-50',    'fg' => 'text-blue-500',
                           'icon' => '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>'],
            'log'      => ['tag' => 'INFO',    'bg' => 'bg-slate-100',  'fg' => 'text-slate-500',
                           'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
        ];
        foreach ($map as $keyword => $style) {
            if (str_contains($action, $keyword)) {
                return $style;
            }
        }
        return ['tag' => 'INFO', 'bg' => 'bg-slate-100', 'fg' => 'text-slate-500',
                'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'];
    };

    $prettyAction = fn (string $action) => ucwords(str_replace('_', ' ', $action));
@endphp

{{-- ══════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════ --}}
<div class="flex items-center justify-between flex-wrap gap-3 mb-6">
  <div>
    <h1 class="text-[22px] font-extrabold tracking-tight text-slate-900">{{ $logTitle }}</h1>
    <p class="text-[13px] text-slate-500 mt-0.5">
      {{ $isAdminLog ? 'Riwayat tindakan yang dilakukan oleh akun admin.' : 'Riwayat aktivitas yang dilakukan oleh member dan mentor.' }}
    </p>
  </div>
  <div>
    <a href="{{ route($logRoute, array_merge(request()->query(), ['export' => 'csv'])) }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[13px] font-semibold
              bg-white border border-slate-200 text-slate-700 hover:bg-slate-50
              transition-all duration-200 cursor-pointer no-underline">
      <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
      </svg>
      Export CSV
    </a>
  </div>
</div>

{{-- ══════════════════════════════════════════
     STAT CARDS
══════════════════════════════════════════ --}}
<div class="grid grid-cols-4 gap-4 mb-6">

  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5 shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#1d9e75;">
    <div class="w-10 h-10 rounded-[10px] bg-primary-light flex items-center justify-center">
      <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <polyline points="20 6 9 17 4 12"/>
      </svg>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ number_format($stats['total_today']) }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Total Log Hari Ini</div>
      <div class="text-[11px] text-slate-400 mt-0.5">Semua aksi tercatat</div>
    </div>
  </div>

  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5 shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#3b82f6;">
    <div class="w-10 h-10 rounded-[10px] bg-blue-50 flex items-center justify-center">
      <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
      </svg>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ number_format($stats['active_admins']) }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Admin Aktif</div>
      <div class="text-[11px] text-slate-400 mt-0.5">Melakukan aksi hari ini</div>
    </div>
  </div>

  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5 shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#f59e0b;">
    <div class="w-10 h-10 rounded-[10px] bg-amber-50 flex items-center justify-center">
      <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
      </svg>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ number_format($stats['update_today']) }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Aksi Update</div>
      <div class="text-[11px] text-slate-400 mt-0.5">{{ $stats['update_pct'] }}% dari total</div>
    </div>
  </div>

  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5 shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#ef4444;">
    <div class="w-10 h-10 rounded-[10px] bg-red-50 flex items-center justify-center">
      <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
      </svg>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ number_format($stats['delete_today']) }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Aksi Hapus</div>
      <div class="text-[11px] text-slate-400 mt-0.5">Perlu perhatian khusus</div>
    </div>
  </div>

</div>

{{-- ══════════════════════════════════════════
     FILTER BAR
══════════════════════════════════════════ --}}
<form method="GET" action="{{ route($logRoute) }}" class="flex gap-2.5 mb-5 flex-wrap items-center">

  @if($isAdminLog)
    <select name="admin_id" onchange="this.form.submit()"
            class="px-3.5 py-2 border border-slate-200 rounded-lg text-[13px] bg-white text-slate-700 cursor-pointer outline-none">
      <option value="">Semua Admin</option>
      @foreach($admins as $admin)
        <option value="{{ $admin->id }}" @selected(request('admin_id') == $admin->id)>{{ $admin->full_name }}</option>
      @endforeach
    </select>
  @endif

  <select name="action_type" onchange="this.form.submit()"
          class="px-3.5 py-2 border border-slate-200 rounded-lg text-[13px] bg-white text-slate-700 cursor-pointer outline-none">
    <option value="">Semua Aksi</option>
    @foreach(['creat' => 'Create', 'update' => 'Update', 'delete' => 'Delete', 'verify' => 'Verify', 'suspend' => 'Suspend', 'login' => 'Login'] as $val => $label)
      <option value="{{ $val }}" @selected(request('action_type') === $val)>{{ $label }}</option>
    @endforeach
  </select>

  <select name="target_table" onchange="this.form.submit()"
          class="px-3.5 py-2 border border-slate-200 rounded-lg text-[13px] bg-white text-slate-700 cursor-pointer outline-none">
    <option value="">Semua Tabel</option>
    @foreach($targetTables as $table)
      <option value="{{ $table }}" @selected(request('target_table') === $table)>{{ $table }}</option>
    @endforeach
  </select>

  <select name="period" onchange="this.form.submit()"
          class="px-3.5 py-2 border border-slate-200 rounded-lg text-[13px] bg-white text-slate-700 cursor-pointer outline-none">
    <option value="today" @selected($period === 'today')>Hari Ini</option>
    <option value="7d" @selected($period === '7d')>7 Hari Terakhir</option>
    <option value="30d" @selected($period === '30d')>30 Hari Terakhir</option>
  </select>

  <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari admin, aksi, tabel target..."
         class="px-3.5 py-2 border border-slate-200 rounded-lg text-[13px] w-[240px] outline-none focus:border-primary" />

  <button type="submit" class="px-3.5 py-2 rounded-lg text-[13px] font-semibold bg-primary text-white cursor-pointer">Terapkan</button>

  @if(request()->anyFilled(['admin_id', 'action_type', 'target_table', 'q']) || request('period', 'today') !== 'today')
    <a href="{{ route($logRoute) }}" class="text-[12px] text-slate-500 hover:text-slate-700 no-underline">Reset</a>
  @endif

  <div class="ml-auto text-[12px] text-slate-400">
    Menampilkan {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} dari {{ number_format($logs->total()) }} log
  </div>
</form>

{{-- ══════════════════════════════════════════
     LOG TIMELINE
══════════════════════════════════════════ --}}
<div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
  <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
    <div class="flex items-center gap-2.5">
      <div class="w-[30px] h-[30px] rounded-lg bg-slate-100 flex items-center justify-center">
        <svg class="w-[15px] h-[15px] text-slate-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
        </svg>
      </div>
      <div>
        <div class="text-[14px] font-bold text-slate-900">Aktivitas Terbaru</div>
        <div class="text-[11px] text-slate-400">{{ now()->locale('id')->isoFormat('dddd, D MMM YYYY') }}</div>
      </div>
    </div>
  </div>

  <div class="flex flex-col">
    @forelse($logs as $log)
      @php $style = $styleFor($log->action); @endphp
      <div class="flex gap-3.5 px-5 py-3.5 border-b border-slate-100 last:border-b-0 hover:bg-slate-50 transition-colors duration-150">
        <div class="w-9 h-9 rounded-[10px] {{ $style['bg'] }} flex items-center justify-center flex-shrink-0">
          <svg class="w-[17px] h-[17px] {{ $style['fg'] }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            {!! $style['icon'] !!}
          </svg>
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex items-center justify-between gap-2 mb-0.5">
            <div class="text-[13px] font-semibold text-slate-900">{{ $prettyAction($log->action) }}</div>
            <div class="text-[11px] text-slate-400 whitespace-nowrap flex-shrink-0">{{ $log->performed_at->diffForHumans() }}</div>
          </div>
          @if($log->details)
            <div class="text-[12px] text-slate-500 leading-relaxed">{{ $log->details }}</div>
          @endif
          <div class="flex gap-2 mt-1.5 flex-wrap">
            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-md bg-slate-100 text-slate-500">{{ $style['tag'] }}</span>
            @if($log->target_table)
              <span class="text-[10px] font-semibold px-2 py-0.5 rounded-md bg-slate-100 text-slate-500">{{ $log->target_table }}</span>
            @endif
            @if(!empty($log->target_id))
              <span class="text-[10px] font-semibold px-2 py-0.5 rounded-md bg-slate-100 text-slate-500">ID: {{ Str::limit((string) $log->target_id, 8, '') }}</span>
            @endif
          </div>
          <div class="text-[11px] text-slate-400 mt-1">
            oleh <strong class="text-slate-600">{{ $log->admin?->full_name ?? 'Sistem' }}</strong>@if($log->ip_address) · {{ $log->ip_address }}@endif
          </div>
        </div>
      </div>
    @empty
      <div class="px-5 py-12 text-center text-[13px] text-slate-400">Tidak ada log yang cocok dengan filter ini.</div>
    @endforelse
  </div>

  @if($logs->hasPages())
    <div class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100">
      <div class="text-[12px] text-slate-400">Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ number_format($logs->total()) }} log</div>
      <div>{{ $logs->onEachSide(1)->links() }}</div>
    </div>
  @endif
</div>

@endsection
