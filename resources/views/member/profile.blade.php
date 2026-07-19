@extends('layouts.app')

@section('title', 'Profil Saya | Planet Fitness')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />
<style>[x-cloak] { display: none !important; }</style>

@include('partials.navbar')

<div
    class="min-h-screen bg-slate-50 text-slate-900 antialiased font-sans"
    x-data="{
        preview: {{ \Illuminate\Support\Js::from($member->profile_photo_url) }},
        onFileChange(e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (ev) => { this.preview = ev.target.result; };
            reader.readAsDataURL(file);
        },
    }"
>
    {{-- ═══════════ Hero gelap ala Apple ═══════════ --}}
    <div class="relative mesh-dark noise-overlay overflow-hidden">
        <div class="absolute -top-16 right-[8%] w-72 h-72 orb animate-orb-float-slow"></div>
        <div class="absolute bottom-[-4rem] left-[6%] w-52 h-52 orb-mini opacity-40 animate-float-y"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 pt-20 pb-16 reveal-on-scroll">
            <div class="text-xs font-bold text-primary-300 tracking-[0.2em] uppercase mb-3">Pengaturan Akun</div>
            <h1 class="display-heading text-4xl sm:text-5xl font-extrabold text-white max-w-2xl">Profil Saya</h1>
            <p class="text-[15px] text-white/60 mt-4 max-w-xl leading-relaxed">
                Perbarui biodata dan foto profilmu. Informasi ini dipakai untuk mempersonalisasi pengalamanmu di Planet Fitness.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-10 pb-20 -mt-6">
        <div class="grid lg:grid-cols-3 gap-6">

            {{-- ─── Kartu Avatar & Ringkasan ─────────────────────────────── --}}
            <div class="h-fit bg-white border border-slate-200 rounded-3xl overflow-hidden">
                <div class="relative h-24 bg-gradient-to-br from-emerald-600 via-emerald-700 to-ink-900 overflow-hidden">
                    <div class="absolute -top-6 -right-4 w-28 h-28 rounded-full bg-white/10 blur-xl"></div>
                </div>

                <div class="flex flex-col items-center text-center px-6 pb-6">
                    <div class="relative -mt-10 mb-4">
                        <template x-if="preview">
                            <img :src="preview" alt="{{ $member->full_name }}"
                                 class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-elevated" />
                        </template>
                        <template x-if="!preview">
                            <div class="w-20 h-20 rounded-full border-4 border-white shadow-elevated bg-primary
                                        flex items-center justify-center text-xl font-bold text-white">
                                {{ collect(explode(' ', $member->full_name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->join('') }}
                            </div>
                        </template>

                        <label for="photo-input"
                               class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-ink-900 text-white
                                      flex items-center justify-center cursor-pointer border-2 border-white
                                      hover:bg-ink-800 transition-colors duration-150">
                            <i class="ti ti-camera text-[13px]"></i>
                        </label>
                    </div>

                    <h3 class="text-base font-bold text-slate-900">{{ $member->full_name }}</h3>
                    <p class="text-xs text-slate-400 mt-1">{{ $user->email }}</p>

                    <div class="mt-4">
                        @if ($member->subscription_type === 'premium')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-violet-50 text-violet-700">
                                <i class="ti ti-crown"></i> Premium
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-primary-light text-primary-dark">
                                Free
                            </span>
                        @endif
                    </div>

                    @if ($member->profile_photo_url)
                        <form method="POST" action="{{ route('member.profile.photo.destroy') }}"
                              onsubmit="return confirm('Hapus foto profil?');" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-600 cursor-pointer">
                                Hapus foto profil
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- ─── Form Biodata ─────────────────────────────────────────── --}}
            <div class="lg:col-span-2 bg-white border border-slate-200 rounded-3xl p-6">
                <h3 class="text-sm font-bold text-slate-900 mb-5">Perbarui Informasi</h3>

                <form method="POST" action="{{ route('member.profile.update') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Input file tersembunyi, dipicu lewat ikon kamera di kartu avatar --}}
                    <input id="photo-input" type="file" name="photo" accept="image/png,image/jpeg,image/webp"
                           class="hidden" @change="onFileChange">
                    @error('photo')
                        <p class="text-xs text-red-500 -mt-3">{{ $message }}</p>
                    @enderror

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-700">Nama Lengkap</label>
                        <input type="text" name="name" required maxlength="255" value="{{ old('name', $member->full_name) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" />
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-700">Email</label>
                        <input type="email" value="{{ $user->email }}" disabled
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-400" />
                        <p class="text-xs text-slate-400">Email tidak dapat diubah dari halaman ini.</p>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-5">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-700">Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date', $member->birth_date?->format('Y-m-d')) }}"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm
                                          focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" />
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-700">Jenis Kelamin</label>
                            <select name="gender"
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm
                                           focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                <option value="">Pilih...</option>
                                <option value="male" {{ old('gender', $member->gender) === 'male' ? 'selected' : '' }}>Pria</option>
                                <option value="female" {{ old('gender', $member->gender) === 'female' ? 'selected' : '' }}>Wanita</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-5">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-700">Tinggi Badan (cm)</label>
                            <input type="number" step="0.1" min="0" max="300" name="height_cm"
                                   value="{{ old('height_cm', $member->height_cm) }}"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm
                                          focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" />
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-700">Berat Badan (kg)</label>
                            <input type="number" step="0.1" min="0" max="400" name="weight_kg"
                                   value="{{ old('weight_kg', $member->weight_kg) }}"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm
                                          focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" />
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-700">Nomor Telepon</label>
                        <input type="text" name="phone" maxlength="30" value="{{ old('phone', $member->phone) }}"
                               placeholder="mis. 0812xxxxxxx"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" />
                    </div>

                    <div class="flex items-center justify-end pt-2">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl text-sm font-semibold
                                       bg-primary text-white hover:bg-primary-dark shadow-sm transition-all duration-200 cursor-pointer">
                            <i class="ti ti-check"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
