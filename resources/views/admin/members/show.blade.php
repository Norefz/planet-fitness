@extends('admin.layouts.app')

@section('title', $member->full_name)
@section('page_title', 'Manajemen Member')
@section('page_subtitle', $member->full_name)

@section('content')

@php $isActive = $member->user?->is_active ?? false; @endphp

<a href="{{ route('admin.members') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-slate-900 no-underline mb-5">
  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
  Kembali ke Manajemen Member
</a>

<div class="flex items-start justify-between flex-wrap gap-4 mb-6">
  <div class="flex items-center gap-4">
    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-xl font-bold text-white flex-shrink-0"
         style="background: linear-gradient(135deg, #1d9e75, #0f6e56);">
      {{ $member->initials() }}
    </div>
    <div>
      <div class="flex items-center gap-2.5 flex-wrap">
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">{{ $member->full_name }}</h1>
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold
                     {{ $isActive ? 'bg-primary-light text-primary-dark' : 'bg-slate-100 text-slate-500' }}">
          {{ $isActive ? 'Aktif' : 'Tidak Aktif' }}
        </span>
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold
                     {{ $member->subscription_type === 'premium' ? 'bg-violet-50 text-violet-700' : 'bg-primary-light text-primary-dark' }}">
          {{ ucfirst($member->subscription_type) }}
        </span>
      </div>
      <p class="text-sm text-slate-500 mt-1">
        {{ $member->user?->email }}
        @if($member->phone) · {{ $member->phone }} @endif
        · Bergabung {{ $member->created_at->diffForHumans() }}
      </p>
    </div>
  </div>

  <div class="flex gap-2">
    @if($isActive)
      <form method="POST" action="{{ route('admin.members.toggle-active', $member) }}" onsubmit="return confirm('Nonaktifkan member ini?');">
        @csrf @method('PATCH')
        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[13px] font-semibold
                       bg-red-50 text-red-600 hover:bg-red-100 transition-all duration-200 border-none cursor-pointer">
          Nonaktifkan Member
        </button>
      </form>
    @else
      <form method="POST" action="{{ route('admin.members.toggle-active', $member) }}">
        @csrf @method('PATCH')
        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[13px] font-semibold
                       bg-primary text-white hover:bg-primary-dark transition-all duration-200 border-none cursor-pointer">
          Aktifkan Kembali
        </button>
      </form>
    @endif
  </div>
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
  <div class="bg-white border border-slate-200 rounded-xl p-5">
    <div class="text-[24px] font-extrabold text-slate-900">{{ $performance['total_programs'] }}</div>
    <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Program Diikuti</div>
  </div>
  <div class="bg-white border border-slate-200 rounded-xl p-5">
    <div class="text-[24px] font-extrabold text-slate-900">{{ $performance['completed'] }}</div>
    <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Program Selesai</div>
  </div>
  <div class="bg-white border border-slate-200 rounded-xl p-5">
    <div class="text-[24px] font-extrabold text-slate-900">
      {{ $performance['avg_progress'] !== null ? number_format($performance['avg_progress'], 1) . '%' : '—' }}
    </div>
    <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Rata-rata Progres</div>
  </div>
  <div class="bg-white border border-slate-200 rounded-xl p-5">
    <div class="text-[24px] font-extrabold text-slate-900">{{ $performance['total_bookings'] }}</div>
    <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Total Booking Konsultasi</div>
  </div>
</div>

{{-- Enrolled programs --}}
<div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
  <div class="px-5 py-4 border-b border-slate-100">
    <div class="text-[14px] font-bold text-slate-900">Program Latihan Diikuti</div>
    <div class="text-[11px] text-slate-400 mt-0.5">{{ $enrollments->count() }} program</div>
  </div>

  @if($enrollments->isEmpty())
    <div class="px-5 py-10 text-center text-[13px] text-slate-400">Member ini belum mengikuti program latihan apapun.</div>
  @else
    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-slate-50">
          <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Program</th>
          <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Mentor</th>
          <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Progres</th>
          <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach($enrollments as $enrollment)
          <tr class="border-b border-slate-100 last:border-b-0">
            <td class="px-4 py-3 text-[13px] font-semibold text-slate-900">{{ $enrollment->workoutProgram->title ?? '—' }}</td>
            <td class="px-4 py-3 text-[12px] text-slate-500">{{ $enrollment->workoutProgram->mentor->full_name ?? '—' }}</td>
            <td class="px-4 py-3 text-[12px] text-slate-500">{{ $enrollment->progress_pct }}%</td>
            <td class="px-4 py-3">
              <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold
                           {{ $enrollment->status === 'completed' ? 'bg-primary-light text-primary-dark' : 'bg-amber-100 text-amber-800' }}">
                {{ $enrollment->status === 'completed' ? 'Selesai' : 'Aktif' }}
              </span>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>

@endsection
