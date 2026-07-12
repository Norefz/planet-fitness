@csrf

<div class="grid lg:grid-cols-3 gap-6">

  {{-- Main fields --}}
  <div class="lg:col-span-2 space-y-6">

    <x-mentor.card>
      <h3 class="text-sm font-bold text-slate-900 mb-5">Informasi Program</h3>
      <div class="space-y-5">
        <x-mentor.input name="title" label="Judul Program" required maxlength="150"
                         :value="old('title', $program->title)" placeholder="mis. Full Body Fat Burn" />

        <x-mentor.textarea name="description" label="Deskripsi & Gerakan" required :rows="5" maxlength="2000"
                            placeholder="Jelaskan tujuan program, urutan gerakan, dan hal yang perlu diperhatikan member...">{{ old('description', $program->description) }}</x-mentor.textarea>
      </div>
    </x-mentor.card>

    <x-mentor.card>
      <h3 class="text-sm font-bold text-slate-900 mb-1">Jadwal Program</h3>
      <p class="text-xs text-slate-500 mb-5">Detail tiap latihan (video, set, repetisi) diatur terpisah di bagian "Daftar Latihan" di bawah.</p>
      <div class="grid grid-cols-2 gap-4">
        <x-mentor.input type="number" name="duration_weeks" label="Durasi (minggu)" min="1" max="52" :value="old('duration_weeks', $program->duration_weeks)" />
        <x-mentor.input type="number" name="sessions_per_week" label="Sesi / minggu" min="1" max="14" :value="old('sessions_per_week', $program->sessions_per_week)" />
      </div>
    </x-mentor.card>
  </div>

  {{-- Sidebar --}}
  <div class="space-y-6">
    <x-mentor.card>
      <h3 class="text-sm font-bold text-slate-900 mb-5">Klasifikasi</h3>
      <div class="space-y-5">
        @php $cat = old('category', $program->category ?: 'Fat Burn'); @endphp
        <x-mentor.select name="category" label="Kategori" required>
          @foreach (['Fat Burn', 'Kekuatan', 'Core', 'Kardio', 'Fleksibilitas', 'Umum'] as $option)
            <option value="{{ $option }}" @selected($cat === $option)>{{ $option }}</option>
          @endforeach
        </x-mentor.select>

        @php $lvl = old('level', $program->level ?: 'pemula'); @endphp
        <x-mentor.select name="level" label="Tingkat Kesulitan" required>
          <option value="pemula" @selected($lvl === 'pemula')>Pemula</option>
          <option value="menengah" @selected($lvl === 'menengah')>Menengah</option>
          <option value="lanjutan" @selected($lvl === 'lanjutan')>Lanjutan</option>
        </x-mentor.select>
      </div>
    </x-mentor.card>

    <x-mentor.card>
      <h3 class="text-sm font-bold text-slate-900 mb-1.5">Publikasikan</h3>
      <p class="text-xs text-slate-500 mb-5">Simpan sebagai draf untuk melanjutkan nanti, atau publikasikan agar langsung terlihat oleh member.</p>

      <div class="flex flex-col gap-2.5">
        <x-mentor.button type="submit" name="action" value="publish" class="w-full">
          <x-mentor.icon name="check" class="w-4 h-4" />
          {{ $program->exists && $program->isPublished() ? 'Simpan Perubahan' : 'Publikasikan' }}
        </x-mentor.button>
        <x-mentor.button type="submit" name="action" value="draft" variant="secondary" class="w-full">
          Simpan sebagai Draf
        </x-mentor.button>
        <x-mentor.button :href="route('mentor.programs.index')" variant="ghost" class="w-full">
          Batal
        </x-mentor.button>
      </div>
    </x-mentor.card>
  </div>
</div>
