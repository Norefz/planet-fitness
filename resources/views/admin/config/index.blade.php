@extends('admin.layouts.app')

@section('title', 'Konfigurasi Sistem')
@section('page_title', 'Konfigurasi Sistem')
@section('page_subtitle', 'Pengaturan Platform')

@section('content')

@php
    $roleColors = [
        'member'      => ['bg' => 'bg-slate-100',    'fg' => 'text-slate-500'],
        'mentor'      => ['bg' => 'bg-blue-50',      'fg' => 'text-blue-600'],
        'super_admin' => ['bg' => 'bg-primary-light','fg' => 'text-primary-dark'],
    ];
@endphp

<div x-data="{ showAddAdminModal: @js($errors->any() && (old('email') || old('full_name'))) }">

<form method="POST" action="{{ route('admin.config.update') }}">
  @csrf

  {{-- ══════════════════════════════════════════
       PAGE HEADER
  ══════════════════════════════════════════ --}}
  <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
    <div>
      <h1 class="text-[22px] font-extrabold tracking-tight text-slate-900">Konfigurasi Sistem</h1>
      <p class="text-[13px] text-slate-500 mt-0.5">Kelola pengaturan platform, hak akses, dan parameter aplikasi</p>
    </div>
    <button type="submit"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[13px] font-semibold
                   bg-primary text-white shadow-sm hover:bg-primary-dark hover:-translate-y-px
                   transition-all duration-200 cursor-pointer">
      <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <polyline points="20 6 9 17 4 12"/>
      </svg>
      Simpan Perubahan
    </button>
  </div>

  {{-- ══════════════════════════════════════════
       SECTION JUMP NAV (pengganti tab statis)
  ══════════════════════════════════════════ --}}
  <div class="flex gap-1 border-b border-slate-200 mb-6 overflow-x-auto">
    @foreach([
        ['href' => '#platform',  'label' => 'Umum',              'icon' => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>'],
        ['href' => '#booking',   'label' => 'Booking & Konsultasi','icon' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>'],
        ['href' => '#akses',     'label' => 'Hak Akses',          'icon' => '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>'],
        ['href' => '#danger',    'label' => 'Zona Berbahaya',     'icon' => '<path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636-2.87l-8.106-13.536a1.914 1.914 0 0 0-3.274 0z"/><path d="M12 9v4m0 4h.01"/>'],
    ] as $tab)
      <a href="{{ $tab['href'] }}"
         class="px-4 py-2.5 text-[13px] font-semibold text-slate-500 hover:text-slate-700
                border-b-2 border-transparent hover:border-slate-300 flex items-center gap-1.5
                whitespace-nowrap no-underline transition-all duration-150">
        <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">{!! $tab['icon'] !!}</svg>
        {{ $tab['label'] }}
      </a>
    @endforeach
  </div>

  <div class="grid gap-4" style="grid-template-columns: 1fr 300px;">

    {{-- ═══════════════════ LEFT COLUMN ═══════════════════ --}}
    <div class="flex flex-col gap-4">

      {{-- ── Informasi Platform ─────────────────────────── --}}
      <div id="platform" class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden scroll-mt-4">
        <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
          <div class="w-[30px] h-[30px] rounded-lg bg-primary-light flex items-center justify-center">
            <svg class="w-[15px] h-[15px] text-primary" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M18.816 13.58c2.292 2.138 3.546 4 3.092 4.9c-.745 1.46-5.783-.259-11.255-3.838c-5.47-3.579-9.304-7.664-8.56-9.123"/></svg>
          </div>
          <div class="text-[14px] font-bold text-slate-900">Informasi Platform</div>
        </div>
        <div>
          <div class="flex items-center justify-between gap-5 px-5 py-4 border-b border-slate-100">
            <div>
              <div class="text-[13px] font-semibold text-slate-900 mb-0.5">Nama Platform</div>
              <div class="text-[12px] text-slate-400 leading-relaxed max-w-[420px]">Ditampilkan di seluruh halaman, email, dan notifikasi.</div>
            </div>
            <input name="platform_name" type="text" value="{{ old('platform_name', $settings['platform_name']) }}"
                   class="px-3.5 py-2 border border-slate-200 rounded-lg text-[13px] text-slate-700 bg-white w-[220px] outline-none focus:border-primary" />
          </div>
          <div class="flex items-center justify-between gap-5 px-5 py-4 border-b border-slate-100">
            <div>
              <div class="text-[13px] font-semibold text-slate-900 mb-0.5">Email Dukungan</div>
              <div class="text-[12px] text-slate-400 leading-relaxed max-w-[420px]">Email yang dihubungi member/mentor untuk bantuan.</div>
            </div>
            <input name="support_email" type="email" value="{{ old('support_email', $settings['support_email']) }}"
                   class="px-3.5 py-2 border border-slate-200 rounded-lg text-[13px] text-slate-700 bg-white w-[220px] outline-none focus:border-primary" />
          </div>
          <div class="flex items-center justify-between gap-5 px-5 py-4 border-b border-slate-100">
            <div>
              <div class="text-[13px] font-semibold text-slate-900 mb-0.5">Zona Waktu Default</div>
              <div class="text-[12px] text-slate-400 leading-relaxed max-w-[420px]">Digunakan untuk semua jadwal dan log aktivitas sistem.</div>
            </div>
            <select name="default_timezone"
                    class="px-3.5 py-2 border border-slate-200 rounded-lg text-[13px] text-slate-700 bg-white w-[220px] outline-none focus:border-primary cursor-pointer">
              @foreach(['Asia/Jakarta' => 'WIB (UTC+7)', 'Asia/Makassar' => 'WITA (UTC+8)', 'Asia/Jayapura' => 'WIT (UTC+9)'] as $tz => $label)
                <option value="{{ $tz }}" @selected(old('default_timezone', $settings['default_timezone']) === $tz)>{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <div class="flex items-center justify-between gap-5 px-5 py-4">
            <div>
              <div class="text-[13px] font-semibold text-slate-900 mb-0.5">Mode Pemeliharaan</div>
              <div class="text-[12px] text-slate-400 leading-relaxed max-w-[420px]">Nonaktifkan akses publik sementara untuk maintenance.</div>
            </div>
            <x-admin.toggle name="maintenance_mode" :checked="old('maintenance_mode', $settings['maintenance_mode'])" />
          </div>
        </div>
      </div>

      {{-- ── Registrasi & Akun ──────────────────────────── --}}
      <div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
        <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
          <div class="w-[30px] h-[30px] rounded-lg bg-blue-50 flex items-center justify-center">
            <svg class="w-[15px] h-[15px] text-blue-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6M22 11h-6"/></svg>
          </div>
          <div class="text-[14px] font-bold text-slate-900">Registrasi & Akun</div>
        </div>
        <div>
          <div class="flex items-center justify-between gap-5 px-5 py-4 border-b border-slate-100">
            <div>
              <div class="text-[13px] font-semibold text-slate-900 mb-0.5">Registrasi Member Terbuka</div>
              <div class="text-[12px] text-slate-400 leading-relaxed max-w-[420px]">Izinkan pengguna baru mendaftar sebagai member tanpa undangan.</div>
            </div>
            <x-admin.toggle name="member_registration_open" :checked="old('member_registration_open', $settings['member_registration_open'])" />
          </div>
          <div class="flex items-center justify-between gap-5 px-5 py-4 border-b border-slate-100">
            <div>
              <div class="text-[13px] font-semibold text-slate-900 mb-0.5">Registrasi Mentor Terbuka</div>
              <div class="text-[12px] text-slate-400 leading-relaxed max-w-[420px]">Izinkan pendaftaran mentor baru. Tetap memerlukan verifikasi admin.</div>
            </div>
            <x-admin.toggle name="mentor_registration_open" :checked="old('mentor_registration_open', $settings['mentor_registration_open'])" />
          </div>
          <div class="flex items-center justify-between gap-5 px-5 py-4 border-b border-slate-100">
            <div>
              <div class="text-[13px] font-semibold text-slate-900 mb-0.5">Verifikasi Email Wajib</div>
              <div class="text-[12px] text-slate-400 leading-relaxed max-w-[420px]">Akun baru harus verifikasi email sebelum dapat digunakan.</div>
            </div>
            <x-admin.toggle name="email_verification_required" :checked="old('email_verification_required', $settings['email_verification_required'])" />
          </div>
          <div class="flex items-center justify-between gap-5 px-5 py-4 border-b border-slate-100">
            <div>
              <div class="text-[13px] font-semibold text-slate-900 mb-0.5">Login dengan Google</div>
              <div class="text-[12px] text-slate-400 leading-relaxed max-w-[420px]">Izinkan member masuk menggunakan akun Google (OAuth 2.0).</div>
            </div>
            <x-admin.toggle name="google_login_enabled" :checked="old('google_login_enabled', $settings['google_login_enabled'])" />
          </div>
          <div class="flex items-center justify-between gap-5 px-5 py-4">
            <div>
              <div class="text-[13px] font-semibold text-slate-900 mb-0.5">Panjang Minimal Kata Sandi</div>
              <div class="text-[12px] text-slate-400 leading-relaxed max-w-[420px]">Jumlah karakter minimum saat registrasi atau ganti sandi.</div>
            </div>
            <input name="min_password_length" type="number" min="6" max="32"
                   value="{{ old('min_password_length', $settings['min_password_length']) }}"
                   class="px-3.5 py-2 border border-slate-200 rounded-lg text-[13px] text-slate-700 bg-white w-[90px] text-center outline-none focus:border-primary" />
          </div>
        </div>
      </div>

      {{-- ── Booking & Konsultasi ───────────────────────── --}}
      <div id="booking" class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden scroll-mt-4">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
          <div class="flex items-center gap-2.5">
            <div class="w-[30px] h-[30px] rounded-lg bg-purple-50 flex items-center justify-center">
              <svg class="w-[15px] h-[15px] text-purple-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div class="text-[14px] font-bold text-slate-900">Booking & Konsultasi</div>
          </div>
          <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-primary-light text-primary-dark">Gratis · Tanpa Pembayaran</span>
        </div>
        <div>
          <div class="flex items-center justify-between gap-5 px-5 py-4 border-b border-slate-100">
            <div>
              <div class="text-[13px] font-semibold text-slate-900 mb-0.5">Booking Tanpa Biaya</div>
              <div class="text-[12px] text-slate-400 leading-relaxed max-w-[420px]">Member dapat memesan sesi konsultasi mentor tanpa pembayaran apa pun.</div>
            </div>
            <x-admin.toggle name="booking_free" :checked="old('booking_free', $settings['booking_free'])" />
          </div>
          <div class="flex items-center justify-between gap-5 px-5 py-4 border-b border-slate-100">
            <div>
              <div class="text-[13px] font-semibold text-slate-900 mb-0.5">Konfirmasi Otomatis</div>
              <div class="text-[12px] text-slate-400 leading-relaxed max-w-[420px]">Booking langsung berstatus "Dikonfirmasi" tanpa perlu persetujuan mentor.</div>
            </div>
            <x-admin.toggle name="booking_auto_confirm" :checked="old('booking_auto_confirm', $settings['booking_auto_confirm'])" />
          </div>
          <div class="flex items-center justify-between gap-5 px-5 py-4 border-b border-slate-100">
            <div>
              <div class="text-[13px] font-semibold text-slate-900 mb-0.5">Maks. Booking Aktif per Member</div>
              <div class="text-[12px] text-slate-400 leading-relaxed max-w-[420px]">Batas jumlah sesi yang bisa dijadwalkan member dalam waktu bersamaan.</div>
            </div>
            <input name="booking_max_active_per_member" type="number" min="1" max="20"
                   value="{{ old('booking_max_active_per_member', $settings['booking_max_active_per_member']) }}"
                   class="px-3.5 py-2 border border-slate-200 rounded-lg text-[13px] text-slate-700 bg-white w-[90px] text-center outline-none focus:border-primary" />
          </div>
          <div class="flex items-center justify-between gap-5 px-5 py-4 border-b border-slate-100">
            <div>
              <div class="text-[13px] font-semibold text-slate-900 mb-0.5">Durasi Sesi Default</div>
              <div class="text-[12px] text-slate-400 leading-relaxed max-w-[420px]">Lama waktu standar untuk satu sesi konsultasi.</div>
            </div>
            <select name="booking_default_duration"
                    class="px-3.5 py-2 border border-slate-200 rounded-lg text-[13px] text-slate-700 bg-white w-[110px] outline-none focus:border-primary cursor-pointer">
              @foreach([30 => '30 menit', 60 => '60 menit', 90 => '90 menit'] as $val => $label)
                <option value="{{ $val }}" @selected((int) old('booking_default_duration', $settings['booking_default_duration']) === $val)>{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <div class="flex items-center justify-between gap-5 px-5 py-4">
            <div>
              <div class="text-[13px] font-semibold text-slate-900 mb-0.5">Batas Waktu Pembatalan</div>
              <div class="text-[12px] text-slate-400 leading-relaxed max-w-[420px]">Member/mentor hanya bisa membatalkan sebelum batas waktu ini.</div>
            </div>
            <select name="booking_cancellation_deadline"
                    class="px-3.5 py-2 border border-slate-200 rounded-lg text-[13px] text-slate-700 bg-white w-[110px] outline-none focus:border-primary cursor-pointer">
              @foreach([1 => '1 jam', 6 => '6 jam', 24 => '24 jam'] as $val => $label)
                <option value="{{ $val }}" @selected((int) old('booking_cancellation_deadline', $settings['booking_cancellation_deadline']) === $val)>{{ $label }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      {{-- ── Hak Akses berdasarkan Role ─────────────────── --}}
      <div id="akses" class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden scroll-mt-4">
        <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
          <div class="w-[30px] h-[30px] rounded-lg bg-amber-50 flex items-center justify-center">
            <svg class="w-[15px] h-[15px] text-amber-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          </div>
          <div>
            <div class="text-[14px] font-bold text-slate-900">Hak Akses berdasarkan Role</div>
            <div class="text-[11px] text-slate-400">Sesuai enum role: member, mentor, super_admin — bersifat tetap, bukan bagian dari form ini</div>
          </div>
        </div>
        <div class="px-5 pb-5 overflow-x-auto">
          <table class="w-full border-collapse">
            <thead>
              <tr>
                <th class="text-left text-[11px] font-bold text-slate-400 uppercase tracking-wide py-2.5 px-3.5 border-b border-slate-100">Aksi</th>
                <th class="text-center text-[11px] font-bold text-slate-400 uppercase tracking-wide py-2.5 px-3.5 border-b border-slate-100">Member</th>
                <th class="text-center text-[11px] font-bold text-slate-400 uppercase tracking-wide py-2.5 px-3.5 border-b border-slate-100">Mentor</th>
                <th class="text-center text-[11px] font-bold text-slate-400 uppercase tracking-wide py-2.5 px-3.5 border-b border-slate-100">Admin</th>
              </tr>
            </thead>
            <tbody>
              @foreach([
                  ['Lihat program latihan',        true,  true,  true],
                  ['Membuat program latihan',      false, true,  true],
                  ['Booking konsultasi mentor',     true,  false, false],
                  ['Menerima/menolak booking',      false, true,  true],
                  ['Verifikasi akun mentor',        false, false, true],
                  ['Mengelola data member',         false, false, true],
                  ['Mengakses log aktivitas',        false, false, true],
                  ['Mengubah konfigurasi sistem',    false, false, true],
              ] as [$label, $member, $mentor, $admin])
                <tr>
                  <td class="text-[13px] text-slate-700 py-3 px-3.5 border-b border-slate-50">{{ $label }}</td>
                  @foreach([$member, $mentor, $admin] as $can)
                    <td class="text-center py-3 px-3.5 border-b border-slate-50">
                      <span class="inline-flex w-5 h-5 rounded-md items-center justify-center {{ $can ? 'bg-primary-light text-primary' : 'bg-slate-100 text-slate-300' }}">
                        @if($can)
                          <svg class="w-[11px] h-[11px]" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        @else
                          <svg class="w-[11px] h-[11px]" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        @endif
                      </span>
                    </td>
                  @endforeach
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

    </div>

    {{-- ═══════════════════ RIGHT COLUMN ═══════════════════ --}}
    <div class="flex flex-col gap-4">

      {{-- ── Akun Admin ─────────────────────────────────── --}}
      <div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
        <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
          <div class="w-[30px] h-[30px] rounded-lg bg-purple-50 flex items-center justify-center">
            <svg class="w-[14px] h-[14px] text-purple-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
          </div>
          <div class="text-[14px] font-bold text-slate-900">Akun Admin</div>
        </div>
        <div>
          @forelse($admins as $admin)
            <div class="flex items-center gap-3.5 px-5 py-3.5 border-b border-slate-100 last:border-b-0">
              <div class="w-[34px] h-[34px] rounded-full bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center text-[12px] font-bold text-white flex-shrink-0">
                {{ strtoupper(substr($admin->full_name, 0, 2)) }}
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-[13px] font-semibold text-slate-900 truncate">{{ $admin->full_name }}</div>
                <div class="text-[11px] text-slate-400 truncate">{{ $admin->employee_id ?? '—' }}</div>
              </div>
              <span class="text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide flex-shrink-0
                           {{ $admin->is_head ? 'bg-primary-light text-primary-dark' : 'bg-blue-50 text-blue-700' }}">
                {{ $admin->title ?? ($admin->is_head ? 'Super Admin' : 'Admin') }}
              </span>
            </div>
          @empty
            <div class="px-5 py-6 text-center text-[13px] text-slate-400">Belum ada akun admin.</div>
          @endforelse
          <div class="px-5 py-3.5">
            @if($isHeadAdmin)
              <button type="button" @click="showAddAdminModal = true"
                      class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-[13px] font-semibold
                             bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-300
                             transition-all duration-200 cursor-pointer">
                <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Admin
              </button>
            @else
              <button type="button" disabled title="Hanya Super Admin utama yang dapat menambah akun admin"
                      class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-[13px] font-semibold
                             bg-white border border-slate-200 text-slate-300 cursor-not-allowed">
                <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Admin
              </button>
            @endif
          </div>
        </div>
      </div>

      {{-- ── Informasi Sistem ───────────────────────────── --}}
      <div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
        <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
          <div class="w-[30px] h-[30px] rounded-lg bg-slate-100 flex items-center justify-center">
            <svg class="w-[14px] h-[14px] text-slate-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
          </div>
          <div class="text-[14px] font-bold text-slate-900">Informasi Sistem</div>
        </div>
        <div class="px-5 pt-1.5 pb-4 flex flex-col gap-3">
          <div class="flex items-center justify-between text-[12px]">
            <span class="text-slate-400">Versi Aplikasi</span>
            <span class="font-bold text-slate-700">{{ $systemInfo['app_version'] }}</span>
          </div>
          <div class="flex items-center justify-between text-[12px]">
            <span class="text-slate-400">Framework</span>
            <span class="font-bold text-slate-700">{{ $systemInfo['framework'] }}</span>
          </div>
          <div class="flex items-center justify-between text-[12px]">
            <span class="text-slate-400">Database</span>
            <span class="font-bold text-slate-700">{{ $systemInfo['database'] }}</span>
          </div>
          <div class="flex items-center justify-between text-[12px]">
            <span class="text-slate-400">Migrasi Terakhir</span>
            <span class="font-bold text-slate-700">{{ $systemInfo['last_update'] }}</span>
          </div>
          @if($isHeadAdmin)
            <div class="flex items-center justify-between text-[12px]">
              <span class="text-slate-400">Total Log Aktivitas</span>
              <span class="font-bold text-slate-700">{{ number_format($systemInfo['total_logs']) }}</span>
            </div>
          @endif
          <div class="flex items-center justify-between text-[12px]">
            <span class="text-slate-400">Status Server</span>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary-light text-primary-dark">
              <svg class="w-[7px] h-[7px]" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/></svg>
              Operasional
            </span>
          </div>
        </div>
      </div>

    </div>

  </div>

</form>

{{-- ══════════════════════════════════════════
     DANGER ZONE — di luar form utama, masing-masing
     tombol punya form POST + konfirmasi sendiri.
══════════════════════════════════════════ --}}
<div id="danger" class="mt-4 border border-red-200 rounded-xl bg-red-50 p-5 scroll-mt-4">
  <div class="flex items-center gap-2 text-[13px] font-bold text-red-700 mb-1">
    <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636-2.87l-8.106-13.536a1.914 1.914 0 0 0-3.274 0z"/><path d="M12 9v4m0 4h.01"/></svg>
    Zona Berbahaya
  </div>
  <div class="text-[12px] text-red-600 mb-3.5 leading-relaxed">
    Tindakan di bawah ini bersifat permanen dan tidak dapat dibatalkan. Pastikan kamu benar-benar yakin sebelum melanjutkan.
  </div>
  <div class="flex gap-2.5 flex-wrap">
    @if($isHeadAdmin)
      <form method="POST" action="{{ route('admin.config.clear-logs') }}"
            onsubmit="return confirm('Hapus SEMUA log aktivitas secara permanen? Tindakan ini tidak bisa dibatalkan.');">
        @csrf
        <button type="submit"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[13px] font-semibold
                       bg-white border border-red-300 text-red-700 hover:bg-red-100 transition-all duration-200 cursor-pointer">
          <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
          Hapus Semua Log Aktivitas
        </button>
      </form>
    @endif
    <form method="POST" action="{{ route('admin.config.reset') }}"
          onsubmit="return confirm('Kembalikan seluruh konfigurasi ke nilai default?');">
      @csrf
      <button type="submit"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[13px] font-semibold
                     bg-white border border-red-300 text-red-700 hover:bg-red-100 transition-all duration-200 cursor-pointer">
        <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        Reset Konfigurasi ke Default
      </button>
    </form>
  </div>
</div>

{{-- ══════════════════════════════════════════
     MODAL: TAMBAH ADMIN
     (di luar form utama supaya tidak nested <form>)
══════════════════════════════════════════ --}}
<div x-show="showAddAdminModal" x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center px-4"
     style="display:none;">
  <div class="absolute inset-0 bg-slate-900/40" @click="showAddAdminModal = false"></div>

  <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">
    <form method="POST" action="{{ route('admin.config.admins.store') }}">
      @csrf

      <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <div class="text-[15px] font-bold text-slate-900">Tambah Admin</div>
        <button type="button" @click="showAddAdminModal = false"
                class="text-slate-400 hover:text-slate-600 cursor-pointer">
          <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <div class="px-5 py-4 flex flex-col gap-3.5 max-h-[70vh] overflow-y-auto">
        <p class="text-[12px] text-slate-500 leading-relaxed">
          Akun baru akan dibuat sebagai <strong>Admin</strong> (bukan Super Admin utama) dan langsung bisa
          digunakan untuk masuk ke panel admin.
        </p>

        <div>
          <label class="block text-[12px] font-semibold text-slate-600 mb-1">Nama Lengkap</label>
          <input name="full_name" type="text" value="{{ old('full_name') }}" required
                 class="w-full px-3 py-2 rounded-lg border text-[13px] outline-none transition-all
                        {{ $errors->has('full_name') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/15' }}" />
          @error('full_name')<div class="text-[11px] text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
          <label class="block text-[12px] font-semibold text-slate-600 mb-1">Email</label>
          <input name="email" type="email" value="{{ old('email') }}" required
                 class="w-full px-3 py-2 rounded-lg border text-[13px] outline-none transition-all
                        {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/15' }}" />
          @error('email')<div class="text-[11px] text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-[12px] font-semibold text-slate-600 mb-1">Kata Sandi</label>
            <input name="password" type="password" minlength="8" required
                   class="w-full px-3 py-2 rounded-lg border text-[13px] outline-none transition-all
                          {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-slate-200 focus:border-primary focus:ring-2 focus:ring-primary/15' }}" />
            @error('password')<div class="text-[11px] text-red-600 mt-1">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="block text-[12px] font-semibold text-slate-600 mb-1">Konfirmasi Sandi</label>
            <input name="password_confirmation" type="password" minlength="8" required
                   class="w-full px-3 py-2 rounded-lg border border-slate-200 text-[13px] outline-none transition-all
                          focus:border-primary focus:ring-2 focus:ring-primary/15" />
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-[12px] font-semibold text-slate-600 mb-1">Jabatan</label>
            <input name="title" type="text" value="{{ old('title') }}" placeholder="Admin"
                   class="w-full px-3 py-2 rounded-lg border border-slate-200 text-[13px] outline-none transition-all
                          focus:border-primary focus:ring-2 focus:ring-primary/15" />
          </div>
          <div>
            <label class="block text-[12px] font-semibold text-slate-600 mb-1">ID Karyawan</label>
            <input name="employee_id" type="text" value="{{ old('employee_id') }}"
                   class="w-full px-3 py-2 rounded-lg border border-slate-200 text-[13px] outline-none transition-all
                          focus:border-primary focus:ring-2 focus:ring-primary/15" />
          </div>
        </div>
      </div>

      <div class="flex items-center justify-end gap-2 px-5 py-4 border-t border-slate-100 bg-slate-50">
        <button type="button" @click="showAddAdminModal = false"
                class="px-4 py-2 rounded-lg text-[13px] font-semibold text-slate-600 hover:bg-slate-100 cursor-pointer">
          Batal
        </button>
        <button type="submit"
                class="px-4 py-2 rounded-lg text-[13px] font-semibold bg-primary text-white shadow-sm hover:bg-primary-dark cursor-pointer">
          Simpan Admin
        </button>
      </div>
    </form>
  </div>
</div>

</div>{{-- /x-data wrapper --}}

@endsection
