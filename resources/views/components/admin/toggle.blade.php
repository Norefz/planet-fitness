@props(['name', 'checked' => false])

{{--
    Toggle switch berbasis native checkbox (tanpa JS) — cukup ditaruh di
    dalam <form>, statusnya ikut terkirim sebagai boolean saat submit.
    Dipakai lewat: <x-admin.toggle name="maintenance_mode" :checked="$value" />
--}}
<label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
    <input type="checkbox" name="{{ $name }}" value="1" @checked($checked) class="peer sr-only">
    <span class="w-11 h-6 rounded-full bg-slate-200 peer-checked:bg-primary transition-colors duration-200"></span>
    <span class="absolute left-[3px] top-[3px] w-[18px] h-[18px] rounded-full bg-white shadow
                 transition-transform duration-200 peer-checked:translate-x-5"></span>
</label>
