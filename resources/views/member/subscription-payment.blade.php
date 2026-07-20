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
        <button id="pay-button" type="button" class="mt-6 w-full flex items-center justify-center gap-2 rounded-xl bg-ink-900 hover:bg-black disabled:bg-slate-400 text-white py-3.5 text-sm font-semibold transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"/></svg>
          Bayar Sekarang
        </button>
        <p id="payment-message" class="hidden mt-3 text-xs text-amber-700"></p>
        <form method="POST" action="{{ route('member.payment.retry') }}" class="mt-3">@csrf<button type="submit" class="text-xs text-slate-500 hover:text-slate-700">Buat transaksi baru</button></form>
      @endif

      <form method="POST" action="{{ route('member.logout') }}" class="mt-6">@csrf<button type="submit" class="text-xs text-slate-400 hover:text-slate-600">Keluar dari akun</button></form>
    @endif
  </div>
</div>

@if(! $member->hasActiveSubscription() && $payment?->snap_token)
  <div id="payment-loading" class="hidden fixed inset-0 z-[200] items-center justify-center bg-slate-950/55 backdrop-blur-sm px-4">
    <div class="w-full max-w-sm rounded-3xl bg-white p-7 text-center shadow-2xl">
      <div class="mx-auto w-12 h-12 rounded-full border-4 border-primary-light border-t-primary animate-spin"></div>
      <h2 class="mt-5 text-lg font-extrabold text-slate-900">Menyiapkan pembayaran</h2>
      <p class="mt-2 text-sm text-slate-500">Membuka pilihan pembayaran aman dari Midtrans…</p>
    </div>
  </div>

  @push('scripts')
    <script id="midtrans-snap" src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
      (() => {
        const button = document.getElementById('pay-button');
        const loading = document.getElementById('payment-loading');
        const message = document.getElementById('payment-message');
        const snapToken = @json($payment->snap_token);
        const redirectUrl = @json($payment->snap_redirect_url);

        const setLoading = (isLoading) => {
          loading.classList.toggle('hidden', !isLoading);
          loading.classList.toggle('flex', isLoading);
          button.disabled = isLoading;
        };

        button.addEventListener('click', () => {
          setLoading(true);

          // Fallback tetap mengarah ke Snap Redirect jika SDK gagal dimuat,
          // sehingga pembayaran tidak pernah berhenti hanya karena JavaScript.
          if (!window.snap) {
            if (redirectUrl) {
              window.location.assign(redirectUrl);
              return;
            }

            setLoading(false);
            message.textContent = 'Popup Midtrans belum tersedia. Buat transaksi baru lalu coba lagi.';
            message.classList.remove('hidden');
            return;
          }

          window.snap.pay(snapToken, {
            onSuccess: () => window.location.reload(),
            onPending: () => window.location.reload(),
            onError: () => window.location.reload(),
            onClose: () => setLoading(false),
          });
        });
      })();
    </script>
  @endpush
@endif

@endsection
