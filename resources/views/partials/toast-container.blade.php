{{--
    Partial: resources/views/partials/toast-container.blade.php

    Sistem notifikasi toast terpusat. Otomatis menampilkan flash session
    ('success' / 'error') saat halaman dimuat, dan bisa dipicu dari JS
    kapan saja lewat:

        window.dispatchEvent(new CustomEvent('pf-toast', {
            detail: { type: 'success', message: 'Tersimpan!' }
        }))

    atau helper singkat:  pfToast('success', 'Tersimpan!')
--}}
<div
    x-data="{
        toasts: [],
        push(type, message) {
            if (!message) return;
            const id = Date.now() + Math.random();
            this.toasts.push({ id, type, message });
            setTimeout(() => this.dismiss(id), 4500);
        },
        dismiss(id) { this.toasts = this.toasts.filter(t => t.id !== id); },
    }"
    x-init='
        @if (session('success')) push("success", @json(session('success'))); @endif
        @if (session('error'))   push("error",   @json(session('error')));   @endif
        window.addEventListener("pf-toast", (e) => push(e.detail.type ?? "success", e.detail.message));
    '
    class="pointer-events-none fixed inset-x-0 top-4 z-[200] flex flex-col items-center gap-2 px-4 sm:top-6 sm:items-end sm:px-6"
    aria-live="polite"
    aria-atomic="true"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-3 sm:translate-y-0 sm:translate-x-6"
            x-transition:enter-end="opacity-100 translate-y-0 translate-x-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-2xl border bg-white p-4 shadow-dropdown"
            :class="{
                'border-red-100': toast.type === 'error',
                'border-amber-100': toast.type === 'warning',
                'border-primary-100': toast.type === 'success',
            }"
        >
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full"
                 :class="{
                    'bg-primary-50 text-primary-600': toast.type === 'success',
                    'bg-red-50 text-red-600': toast.type === 'error',
                    'bg-amber-50 text-amber-600': toast.type === 'warning',
                 }">
                <svg x-show="toast.type === 'success'" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                <svg x-show="toast.type === 'error'" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                <svg x-show="toast.type === 'warning'" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
            </div>
            <p class="flex-1 pt-1 text-sm font-medium text-slate-700" x-text="toast.message"></p>
            <button @click="dismiss(toast.id)" class="rounded-full p-1 text-slate-300 transition hover:bg-slate-100 hover:text-slate-500" aria-label="Tutup notifikasi">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
    </template>
</div>

<script>
    // Helper global kecil supaya halaman lain cukup panggil: pfToast('success', 'Pesan...')
    function pfToast(type, message) {
        window.dispatchEvent(new CustomEvent('pf-toast', { detail: { type, message } }));
    }
</script>
