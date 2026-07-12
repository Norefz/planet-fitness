<div class="mt-10">
  <div class="flex items-center justify-between flex-wrap gap-3 mb-4">
    <div>
      <h3 class="text-base font-bold text-slate-900">Daftar Latihan</h3>
      <p class="text-xs text-slate-500 mt-1 max-w-md">Tambahkan lebih dari satu jenis latihan — masing-masing dengan video dan detail set/repetisinya sendiri.</p>
    </div>
    <x-mentor.button type="button" size="sm" onclick="document.getElementById('add-exercise').showModal()">
      <x-mentor.icon name="plus" class="w-3.5 h-3.5" /> Tambah Latihan
    </x-mentor.button>
  </div>

  @if ($program->exercises->isEmpty())
    <x-mentor.empty-state icon="video" title="Belum ada latihan" description="Tambahkan jenis latihan pertama untuk program ini, lengkap dengan video panduannya.">
      <x-mentor.button size="sm" type="button" onclick="document.getElementById('add-exercise').showModal()">
        <x-mentor.icon name="plus" class="w-3.5 h-3.5" /> Tambah Latihan
      </x-mentor.button>
    </x-mentor.empty-state>
  @else
    <x-mentor.card padding="p-0" class="overflow-hidden">
      @foreach ($program->exercises as $index => $exercise)
        <div class="flex items-center gap-3.5 px-5 py-4 {{ !$loop->first ? 'border-t border-slate-100' : '' }}">

          <div class="flex flex-col shrink-0">
            <form method="POST" action="{{ route('mentor.programs.exercises.move', [$program, $exercise]) }}">
              @csrf @method('PATCH')
              <input type="hidden" name="direction" value="up">
              <button type="submit" {{ $loop->first ? 'disabled' : '' }} title="Naikkan urutan"
                      class="w-6 h-4 flex items-center justify-center text-slate-300 hover:text-slate-600 disabled:opacity-20 disabled:pointer-events-none transition-colors">
                <x-mentor.icon name="chevron-down" class="w-3.5 h-3.5 rotate-180" />
              </button>
            </form>
            <form method="POST" action="{{ route('mentor.programs.exercises.move', [$program, $exercise]) }}">
              @csrf @method('PATCH')
              <input type="hidden" name="direction" value="down">
              <button type="submit" {{ $loop->last ? 'disabled' : '' }} title="Turunkan urutan"
                      class="w-6 h-4 flex items-center justify-center text-slate-300 hover:text-slate-600 disabled:opacity-20 disabled:pointer-events-none transition-colors">
                <x-mentor.icon name="chevron-down" class="w-3.5 h-3.5" />
              </button>
            </form>
          </div>

          <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center text-xs font-bold shrink-0">
            {{ $index + 1 }}
          </div>

          <div class="flex-1 min-w-0">
            <div class="text-sm font-semibold text-slate-900 truncate flex items-center gap-1.5">
              {{ $exercise->name }}
              @if ($exercise->video_url)
                <a href="{{ $exercise->video_url }}" target="_blank" rel="noopener" title="Buka video" class="text-primary-500 hover:text-primary-600 shrink-0">
                  <x-mentor.icon name="video" class="w-3.5 h-3.5" />
                </a>
              @endif
            </div>
            <div class="text-xs text-slate-500 mt-0.5">{{ $exercise->summaryLabel() }}</div>
          </div>

          <div class="flex items-center gap-2 shrink-0">
            <x-mentor.button type="button" variant="secondary" size="icon" title="Edit" onclick="document.getElementById('edit-exercise-{{ $exercise->id }}').showModal()">
              <x-mentor.icon name="edit" class="w-3.5 h-3.5" />
            </x-mentor.button>
            <x-mentor.button type="button" variant="danger" size="icon" title="Hapus" onclick="document.getElementById('del-exercise-{{ $exercise->id }}').showModal()">
              <x-mentor.icon name="trash" class="w-3.5 h-3.5" />
            </x-mentor.button>
          </div>
        </div>
      @endforeach
    </x-mentor.card>
  @endif
</div>

{{-- Add exercise dialog --}}
<dialog id="add-exercise" class="rounded-2xl p-0 w-full max-w-lg backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm" @if ($errors->any() && old('editing_exercise_id') === 'new') open @endif>
  <form method="POST" action="{{ route('mentor.programs.exercises.store', $program) }}" class="p-6">
    <h4 class="text-base font-bold text-slate-900 mb-1">Tambah Latihan</h4>
    <p class="text-xs text-slate-500 mb-5">Tambahkan satu jenis latihan baru ke dalam "{{ $program->title }}".</p>
    @include('mentor.programs._exercise-form', ['exercise' => new \App\Models\WorkoutExercise()])
    <div class="flex justify-end gap-2.5 mt-6">
      <x-mentor.button type="button" variant="secondary" onclick="document.getElementById('add-exercise').close()">Batal</x-mentor.button>
      <x-mentor.button type="submit"><x-mentor.icon name="plus" class="w-4 h-4" /> Tambah Latihan</x-mentor.button>
    </div>
  </form>
</dialog>

{{-- Per-exercise edit & delete dialogs --}}
@foreach ($program->exercises as $exercise)
  <dialog id="edit-exercise-{{ $exercise->id }}" class="rounded-2xl p-0 w-full max-w-lg backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm" @if ($errors->any() && old('editing_exercise_id') === $exercise->id) open @endif>
    <form method="POST" action="{{ route('mentor.programs.exercises.update', [$program, $exercise]) }}" class="p-6">
      @method('PUT')
      <h4 class="text-base font-bold text-slate-900 mb-1">Edit Latihan</h4>
      <p class="text-xs text-slate-500 mb-5">Perbarui detail "{{ $exercise->name }}".</p>
      @include('mentor.programs._exercise-form', ['exercise' => $exercise])
      <div class="flex justify-end gap-2.5 mt-6">
        <x-mentor.button type="button" variant="secondary" onclick="document.getElementById('edit-exercise-{{ $exercise->id }}').close()">Batal</x-mentor.button>
        <x-mentor.button type="submit"><x-mentor.icon name="check" class="w-4 h-4" /> Simpan Perubahan</x-mentor.button>
      </div>
    </form>
  </dialog>

  <dialog id="del-exercise-{{ $exercise->id }}" class="rounded-2xl p-0 w-full max-w-sm backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm">
    <form method="POST" action="{{ route('mentor.programs.exercises.destroy', [$program, $exercise]) }}" class="p-6">
      @csrf @method('DELETE')
      <div class="w-11 h-11 rounded-xl bg-red-50 text-red-600 flex items-center justify-center mb-4">
        <x-mentor.icon name="trash" class="w-5 h-5" />
      </div>
      <h4 class="text-base font-bold text-slate-900 mb-1.5">Hapus latihan ini?</h4>
      <p class="text-sm text-slate-500 mb-6">"{{ $exercise->name }}" akan dihapus dari program ini.</p>
      <div class="flex justify-end gap-2.5">
        <x-mentor.button type="button" variant="secondary" onclick="document.getElementById('del-exercise-{{ $exercise->id }}').close()">Batal</x-mentor.button>
        <x-mentor.button type="submit" variant="danger-solid">Ya, Hapus</x-mentor.button>
      </div>
    </form>
  </dialog>
@endforeach
