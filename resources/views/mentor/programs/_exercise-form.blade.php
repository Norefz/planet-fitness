@csrf
<input type="hidden" name="editing_exercise_id" value="{{ $exercise->exists ? $exercise->id : 'new' }}">
<div class="space-y-5">
  <x-mentor.input name="name" label="Nama Latihan" required maxlength="150"
                   :value="old('name', $exercise->name)" placeholder="mis. Plank, Russian Twist, Jumping Jacks" />

  <x-mentor.input type="url" name="video_url" label="URL Video" icon="video" maxlength="255"
                   :value="old('video_url', $exercise->video_url)" placeholder="https://..." />

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
