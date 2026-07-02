@csrf

<div class="grid lg:grid-cols-3 gap-8">

  {{-- Main fields --}}
  <div class="lg:col-span-2 space-y-6">

    <div class="bg-white border border-slate-200 rounded-2xl p-6">
      <h3 class="text-sm font-bold mb-5">Informasi Program</h3>

      <div class="space-y-5">
        <div class="flex flex-col gap-1.5">
          <label for="title" class="text-xs font-semibold text-slate-700">Judul Program <span class="text-red-500">*</span></label>
          <input type="text" id="title" name="title" value="{{ old('title', $program->title) }}" required maxlength="150"
                 placeholder="mis. Full Body Fat Burn"
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
        </div>

        <div class="flex flex-col gap-1.5">
          <label for="description" class="text-xs font-semibold text-slate-700">Deskripsi & Gerakan <span class="text-red-500">*</span></label>
          <textarea id="description" name="description" rows="5" required maxlength="2000"
                    placeholder="Jelaskan tujuan program, urutan gerakan, dan hal yang perlu diperhatikan member..."
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition resize-none">{{ old('description', $program->description) }}</textarea>
        </div>

        <div class="flex flex-col gap-1.5">
          <label for="video_url" class="text-xs font-semibold text-slate-700">URL Video Panduan</label>
          <div class="relative">
            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
              @include('mentor.partials.icon', ['name' => 'video', 'class' => 'w-4 h-4'])
            </div>
            <input type="url" id="video_url" name="video_url" value="{{ old('video_url', $program->video_url) }}" maxlength="255"
                   placeholder="https://..."
                   class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
          </div>
          <p class="text-xs text-slate-400">Tautan video akan disimpan di penyimpanan cloud (AWS S3) pada implementasi penuh.</p>
        </div>
      </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-6">
      <h3 class="text-sm font-bold mb-5">Detail Latihan</h3>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="flex flex-col gap-1.5">
          <label for="duration_weeks" class="text-xs font-semibold text-slate-700">Durasi (minggu)</label>
          <input type="number" id="duration_weeks" name="duration_weeks" min="1" max="52" value="{{ old('duration_weeks', $program->duration_weeks) }}"
                 class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
        </div>
        <div class="flex flex-col gap-1.5">
          <label for="sessions_per_week" class="text-xs font-semibold text-slate-700">Sesi / minggu</label>
          <input type="number" id="sessions_per_week" name="sessions_per_week" min="1" max="14" value="{{ old('sessions_per_week', $program->sessions_per_week) }}"
                 class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
        </div>
        <div class="flex flex-col gap-1.5">
          <label for="sets" class="text-xs font-semibold text-slate-700">Set</label>
          <input type="number" id="sets" name="sets" min="1" max="99" value="{{ old('sets', $program->sets) }}"
                 class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
        </div>
        <div class="flex flex-col gap-1.5">
          <label for="reps" class="text-xs font-semibold text-slate-700">Repetisi</label>
          <input type="number" id="reps" name="reps" min="1" max="999" value="{{ old('reps', $program->reps) }}"
                 class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
        </div>
      </div>
    </div>
  </div>

  {{-- Sidebar: category, level, publish --}}
  <div class="space-y-6">
    <div class="bg-white border border-slate-200 rounded-2xl p-6">
      <h3 class="text-sm font-bold mb-5">Klasifikasi</h3>

      <div class="flex flex-col gap-1.5 mb-5">
        <label for="category" class="text-xs font-semibold text-slate-700">Kategori <span class="text-red-500">*</span></label>
        <select id="category" name="category" required
                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition bg-white">
          @php $cat = old('category', $program->category ?: 'Fat Burn'); @endphp
          @foreach (['Fat Burn', 'Kekuatan', 'Core', 'Kardio', 'Fleksibilitas', 'Umum'] as $option)
            <option value="{{ $option }}" @selected($cat === $option)>{{ $option }}</option>
          @endforeach
        </select>
      </div>

      <div class="flex flex-col gap-1.5">
        <label for="level" class="text-xs font-semibold text-slate-700">Tingkat Kesulitan <span class="text-red-500">*</span></label>
        <select id="level" name="level" required
                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition bg-white">
          @php $lvl = old('level', $program->level ?: 'pemula'); @endphp
          <option value="pemula" @selected($lvl === 'pemula')>Pemula</option>
          <option value="menengah" @selected($lvl === 'menengah')>Menengah</option>
          <option value="lanjutan" @selected($lvl === 'lanjutan')>Lanjutan</option>
        </select>
      </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-6">
      <h3 class="text-sm font-bold mb-1.5">Publikasikan</h3>
      <p class="text-xs text-slate-500 mb-5">Simpan sebagai draf untuk melanjutkan nanti, atau publikasikan agar langsung terlihat oleh member.</p>

      <div class="flex flex-col gap-2.5">
        <button type="submit" name="action" value="publish"
                class="flex items-center justify-center gap-2 w-full bg-primary hover:bg-primary-dark text-white font-semibold text-sm py-3 rounded-xl shadow-sm hover:-translate-y-px transition-all">
          @include('mentor.partials.icon', ['name' => 'check', 'class' => 'w-4 h-4'])
          {{ $program->exists && $program->isPublished() ? 'Simpan Perubahan' : 'Publikasikan' }}
        </button>
        <button type="submit" name="action" value="draft"
                class="flex items-center justify-center gap-2 w-full border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-sm py-3 rounded-xl transition">
          Simpan sebagai Draf
        </button>
        <a href="{{ route('mentor.programs.index') }}"
           class="flex items-center justify-center gap-2 w-full text-slate-500 hover:text-slate-700 font-semibold text-sm py-2 transition">
          Batal
        </a>
      </div>
    </div>
  </div>
</div>
