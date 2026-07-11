<!doctype html>
<html lang="id" class="h-full">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login | Planet Fitness</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
          colors: {
            primary: { DEFAULT: '#1d9e75', dark: '#0f6e56', light: '#e1f5ee' },
            navy:    { DEFAULT: '#0a1628', mid: '#112240' },
          },
        }
      }
    }
  </script>
</head>
<body class="h-full font-sans bg-navy flex">

  {{-- ── Left: branding panel ── --}}
  <div class="hidden lg:flex w-[420px] flex-shrink-0 flex-col justify-between
              bg-gradient-to-br from-navy via-navy-mid to-[#0f3d28]
              p-10 relative overflow-hidden">

    {{-- Background texture --}}
    <div class="absolute inset-0 opacity-[.04]"
         style="background-image:repeating-linear-gradient(45deg,white 0,white 1px,transparent 0,transparent 50%);
                background-size:24px 24px;"></div>

    {{-- Logo --}}
    <div class="relative flex items-center gap-3">
      <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center
                  shadow-[0_0_0_6px_rgba(29,158,117,.2)] flex-shrink-0">
        <svg class="w-[20px] h-[20px] text-white" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M6 5v14M18 5v14M6 8H2M6 16H2M22 8h-4M22 16h-4M6 12h12"/>
        </svg>
      </div>
      <div>
        <div class="text-white font-bold text-[17px] tracking-tight">Planet Fitness</div>
        <div class="text-primary text-[10px] font-bold uppercase tracking-[2px]">Super Admin</div>
      </div>
    </div>

    {{-- Tagline --}}
    <div class="relative">
      <h2 class="text-white text-[28px] font-extrabold leading-tight tracking-tight mb-4">
        Panel Administrasi<br />Platform
      </h2>
      <p class="text-white/50 text-[14px] leading-relaxed">
        Kelola member, verifikasi mentor, pantau program latihan,
        dan monitor seluruh aktivitas platform dari satu tempat.
      </p>

      {{-- Mini stats --}}
      <div class="mt-8 flex gap-6">
        <div>
          <div class="text-[22px] font-extrabold text-primary">1.2K+</div>
          <div class="text-white/40 text-[11px] mt-0.5">Member Aktif</div>
        </div>
        <div>
          <div class="text-[22px] font-extrabold text-primary">47</div>
          <div class="text-white/40 text-[11px] mt-0.5">Mentor Terverifikasi</div>
        </div>
        <div>
          <div class="text-[22px] font-extrabold text-primary">156</div>
          <div class="text-white/40 text-[11px] mt-0.5">Program Aktif</div>
        </div>
      </div>
    </div>

    {{-- Footer note --}}
    <div class="relative text-[11px] text-white/25">
      Akses terbatas · Hanya untuk administrator resmi
    </div>
  </div>

  {{-- ── Right: login form ── --}}
  <div class="flex-1 flex items-center justify-center p-8 bg-slate-100">
    <div class="w-full max-w-[400px]">

      {{-- Card --}}
      <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_4px_24px_rgb(0_0_0/.1)] p-8">

        {{-- Header --}}
        <div class="mb-7">
          <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <rect x="3" y="11" width="18" height="11" rx="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
          </div>
          <h1 class="text-[22px] font-extrabold tracking-tight text-slate-900">Masuk ke Admin Panel</h1>
          <p class="text-[13px] text-slate-500 mt-1.5">
            Akses terbatas untuk administrator resmi Planet Fitness.
          </p>
        </div>

        {{-- Error alert --}}
        @if($errors->any())
          <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-xl
                      px-4 py-3.5 mb-5">
            <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10"/>
              <line x1="12" y1="8" x2="12" y2="12"/>
              <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <div class="text-[13px] text-red-700">{{ $errors->first() }}</div>
          </div>
        @endif

        @if(session('error'))
          <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-xl
                      px-4 py-3.5 mb-5">
            <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10"/>
              <line x1="12" y1="8" x2="12" y2="12"/>
              <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <div class="text-[13px] text-red-700">{{ session('error') }}</div>
          </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.login.post') }}" class="flex flex-col gap-4">
          @csrf

          {{-- Email --}}
          <div class="flex flex-col gap-1.5">
            <label for="email" class="text-[13px] font-semibold text-slate-900">
              Email Admin
            </label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="admin@planetfitness.id"
                   required autocomplete="email"
                   class="px-4 py-3 rounded-xl border text-[14px] text-slate-900
                          placeholder:text-slate-400 outline-none transition-all duration-200
                          {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50 focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/15' }}" />
          </div>

          {{-- Password --}}
          <div class="flex flex-col gap-1.5">
            <label for="password" class="text-[13px] font-semibold text-slate-900">
              Kata Sandi
            </label>
            <div class="relative">
              <input type="password" id="password" name="password"
                     placeholder="••••••••"
                     required autocomplete="current-password"
                     class="w-full px-4 py-3 rounded-xl border text-[14px] text-slate-900
                            placeholder:text-slate-400 outline-none transition-all duration-200
                            {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50 focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/15' }}" />
              {{-- Toggle visibility --}}
              <button type="button" onclick="togglePw()"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400
                             hover:text-slate-600 transition-colors">
                <svg id="pw-eye" class="w-[17px] h-[17px]" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
              </button>
            </div>
          </div>

          {{-- Remember --}}
          <div class="flex items-center justify-between text-[13px]">
            <label class="flex items-center gap-2 text-slate-600 cursor-pointer select-none">
              <input type="checkbox" name="remember" class="accent-primary w-4 h-4 cursor-pointer" />
              Ingat saya
            </label>
          </div>

          {{-- Submit --}}
          <button type="submit"
                  class="w-full flex items-center justify-center gap-2 px-5 py-3 rounded-xl
                         bg-primary hover:bg-primary-dark text-white text-[14px] font-bold
                         transition-all duration-200 hover:-translate-y-0.5
                         hover:shadow-[0_4px_12px_rgba(29,158,117,.4)] cursor-pointer border-none
                         mt-1">
            <svg class="w-[16px] h-[16px]" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
              <polyline points="10 17 15 12 10 7"/>
              <line x1="15" y1="12" x2="3" y2="12"/>
            </svg>
            Masuk ke Dashboard
          </button>
        </form>

        {{-- Security note --}}
        <div class="mt-5 pt-5 border-t border-slate-100 flex items-center gap-2
                    text-[11px] text-slate-400">
          <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor"
               stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <rect x="3" y="11" width="18" height="11" rx="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
          Halaman ini hanya untuk administrator resmi.
          Aktivitas login dicatat dalam sistem audit.
        </div>

      </div>

      {{-- Back to site --}}
      <div class="text-center mt-5">
        <a href="{{ route('home') }}"
           class="text-[13px] text-slate-500 hover:text-slate-700 no-underline
                  flex items-center justify-center gap-1.5 transition-colors duration-200">
          <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor"
               stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/>
          </svg>
          Kembali ke halaman utama
        </a>
      </div>

    </div>
  </div>

</body>

<script>
  function togglePw() {
    const input = document.getElementById('password');
    const eye   = document.getElementById('pw-eye');
    if (input.type === 'password') {
      input.type = 'text';
      eye.innerHTML = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`;
    } else {
      input.type = 'password';
      eye.innerHTML = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
    }
  }
</script>
</html>
