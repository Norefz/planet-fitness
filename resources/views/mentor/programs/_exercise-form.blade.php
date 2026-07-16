@csrf
<input type="hidden" name="editing_exercise_id" value="{{ $exercise->exists ? $exercise->id : 'new' }}">
<div class="space-y-5">
  <x-mentor.input name="name" label="Nama Latihan" required maxlength="150"
                   :value="old('name', $exercise->name)" placeholder="mis. Plank, Russian Twist, Jumping Jacks" />

  <div class="flex flex-col gap-1.5">
    <label for="video-{{ $exercise->exists ? $exercise->id : 'new' }}" class="text-xs font-semibold text-slate-700">
      Video Latihan
    </label>

    @if ($exercise->video_url)
      <div class="rounded-xl border border-primary-100 bg-primary-50/60 p-3 mb-2">
        <div class="flex items-start justify-between gap-3">
          <div class="min-w-0">
            <p class="text-xs font-semibold text-slate-700">Video saat ini</p>
            <a href="{{ $exercise->video_url }}" target="_blank" rel="noopener" class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-primary-600 hover:underline">
              <x-mentor.icon name="video" class="w-3.5 h-3.5 shrink-0" />
              Lihat video yang diunggah
            </a>
            @php $videoName = basename(parse_url($exercise->video_url, PHP_URL_PATH)); @endphp
            @if ($videoName && $videoName !== '/')
              <p class="mt-1 text-[11px] text-slate-500 truncate">{{ urldecode($videoName) }}</p>
            @endif
          </div>
        </div>
        <video controls preload="metadata" src="{{ $exercise->video_url }}" class="mt-3 w-full rounded-lg max-h-40 bg-slate-900"></video>
      </div>
    @endif

    <input type="file" name="video" id="video-{{ $exercise->exists ? $exercise->id : 'new' }}" accept="video/*"
           class="w-full text-sm text-slate-600 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100" />

    <p class="text-xs text-slate-400">
      Format MP4, MOV, AVI, WEBM, atau MKV — maks. 100MB.
      @if ($exercise->video_url) Biarkan kosong jika tidak ingin mengganti video. @endif
    </p>

    @error('video')
      <p class="flex items-center gap-1 text-xs text-red-600">
        <x-mentor.icon name="alert-circle" class="w-3.5 h-3.5 shrink-0" />
        {{ $message }}
      </p>
    @enderror
  </div>

  <div>
    <div class="grid grid-cols-3 gap-3">
      <x-mentor.input type="number" name="sets" label="Set" min="1" max="99" :value="old('sets', $exercise->sets)" />
      <x-mentor.input type="number" name="reps" label="Repetisi" min="1" max="999" :value="old('reps', $exercise->reps)" />
      <x-mentor.input type="number" name="duration_seconds" label="Durasi (detik)" min="1" max="3600" :value="old('duration_seconds', $exercise->duration_seconds)" />
    </div>
    <p class="text-xs text-slate-400 mt-1.5">Isi <strong>Repetisi</strong> untuk latihan hitungan (mis. 12 reps), atau <strong>Durasi</strong> untuk latihan berbasis waktu (mis. plank 45 detik) — tidak perlu isi keduanya.</p>
  </div>

  <x-mentor.input type="number" name="rest_seconds" label="Istirahat Antar Set (detik)" min="0" max="600" :value="old('rest_seconds', $exercise->rest_seconds)" />

  <x-mentor.textarea name="description" label="Cara Melakukan (opsional)" :rows="3" maxlength="1000"
                      placeholder="Jelaskan teknik atau hal yang perlu diperhatikan member...">{{ old('description', $exercise->description) }}</x-mentor.textarea>
</div>
