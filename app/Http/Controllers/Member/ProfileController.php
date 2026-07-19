<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CloudinaryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(private CloudinaryService $cloudinary)
    {
    }

    /**
     * Tampilkan halaman "Profil Saya" milik member yang sedang login.
     */
    public function edit(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $member = $user->member;

        return view('member.profile', compact('user', 'member'));
    }

    /**
     * Perbarui data profil member (biodata + foto profil, jika diunggah).
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $member = $user->member;

        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'gender'     => ['nullable', 'in:male,female'],
            'height_cm'  => ['nullable', 'numeric', 'min:0', 'max:300'],
            'weight_kg'  => ['nullable', 'numeric', 'min:0', 'max:400'],
            'phone'      => ['nullable', 'string', 'max:30'],
            'photo'      => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ]);

        // Nama di tabel users disamakan dengan nama tampilan member, supaya
        // konsisten di seluruh aplikasi (navbar, sidebar admin, dsb).
        $user->update(['name' => $validated['name']]);

        $memberData = [
            'full_name'  => $validated['name'],
            'birth_date' => $validated['birth_date'] ?? null,
            'gender'     => $validated['gender'] ?? null,
            'height_cm'  => $validated['height_cm'] ?? null,
            'weight_kg'  => $validated['weight_kg'] ?? null,
            'phone'      => $validated['phone'] ?? null,
        ];

        // Unggah foto profil baru ke Cloudinary (jika ada), lalu hapus foto
        // lama dari Cloudinary setelah foto baru berhasil tersimpan.
        if ($request->hasFile('photo')) {
            $oldPublicId = $member->profile_photo_public_id;

            $uploaded = $this->cloudinary->uploadImage($request->file('photo'), 'member-profile-pictures');
            $memberData['profile_photo_url']       = $uploaded['url'];
            $memberData['profile_photo_public_id'] = $uploaded['public_id'];

            // Best-effort: kalau foto baru sudah berhasil diunggah, jangan
            // sampai kegagalan membersihkan foto lama (mis. Cloudinary
            // sedang bermasalah) menggagalkan seluruh proses simpan.
            if ($oldPublicId) {
                try {
                    $this->cloudinary->deleteImage($oldPublicId);
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }

        $member->update($memberData);

        return redirect()
            ->route('member.profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Hapus foto profil member (dari Cloudinary sekaligus dari database).
     */
    public function destroyPhoto(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $member = $user->member;

        // Best-effort: kalau Cloudinary sedang bermasalah/belum terkonfigurasi,
        // referensi foto tetap dihapus dari database supaya member tidak
        // "terjebak" tidak bisa mengganti/menghapus foto profilnya.
        if ($member->profile_photo_public_id) {
            try {
                $this->cloudinary->deleteImage($member->profile_photo_public_id);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $member->update([
            'profile_photo_url'       => null,
            'profile_photo_public_id' => null,
        ]);

        return redirect()
            ->route('member.profile.edit')
            ->with('success', 'Foto profil berhasil dihapus.');
    }
}
