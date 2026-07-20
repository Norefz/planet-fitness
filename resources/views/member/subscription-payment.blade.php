@extends('layouts.auth')

@section('title', 'Aktivasi Membership')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-10">
  <div class="w-full max-w-lg bg-white border border-slate-200 rounded-3xl shadow-card-3d p-8 sm:p-10 text-center">
    <div class="w-14 h-14 rounded-full bg-primary-light text-primary flex items-center justify-center mx-auto mb-5">
      <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"/></svg>
    </div>
    <h1 class="text-2xl font-extrabold text-slate-900">Aktivasi Membership</h1>

    @if($member->hasActiveSubscription())
      <p class="mt-3 text-sm text-slate-500">Membership kamu aktif sampai {{ $member->subscription_expires_at->locale('id')->isoFormat('D MMMM YYYY') }}.</p>
      <a href="{{ route('member.dashboard') }}" class="inline-flex mt-6 px-5 py-3 rounded-xl bg-primary text-white font-semibold text-sm">Masuk ke Dashboard</a>
    @else
      <p class="mt-3 text-sm text-slate-500">Selesaikan pembayaran bulanan untuk membuka seluruh fitur Planet Fitness.</p>
      <div class="mt-6 rounded-2xl bg-slate-50 border border-slate-100 px-5 py-4">
        <div class="text-xs uppercase tracking-wide font-bold text-slate-400">Membership Bulanan</div>
        <div class="mt-1 text-3xl font-extrabold text-slate-900">Rp{{ number_format($payment?->amount ?? config('services.midtrans.monthly_price'), 0, ',', '.') }}</div>
        <div class="mt-1 text-xs text-slate-400">Berlaku selama 30 hari setelah pembayaran berhasil.</div>
      </div>

      @if($error)
        <div class="mt-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $error }}</div>
      @elseif($payment?->snap_token)
        <button id="pay-button" class="mt-6 w-full rounded-xl bg-ink-900 hover:bg-black text-white py-3.5 text-sm font-semibold">Bayar Sekarang</button>
        <form method="POST" action="{{ route('member.payment.retry') }}" class="mt-3">@csrf<button type="submit" class="text-xs text-slate-500 hover:text-slate-700">Buat transaksi baru</button></form>
      @endif

      <form method="POST" action="{{ route('member.logout') }}" class="mt-6">@csrf<button type="submit" class="text-xs text-slate-400 hover:text-slate-600">Keluar dari akun</button></form>
    @endif
  </div>
</div>

@if(! $member->hasActiveSubscription() && $payment?->snap_token)
  @push('scripts')
    <script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
      document.getElementById('pay-button').addEventListener('click', () => {
        window.snap.pay(@json($payment->snap_token), {
          onSuccess: () => window.location.reload(),
          onPending: () => window.location.reload(),
          onError: () => window.location.reload(),
          onClose: () => window.location.reload(),
        });
      });
    </script>
  @endpush
@endif
@endsection
