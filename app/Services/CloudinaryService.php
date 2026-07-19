<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CloudinaryService
{
    private ?string $cloudName;
    private ?string $apiKey;
    private ?string $apiSecret;
    private string $folder;

    public function __construct()
    {
        $this->cloudName = config('services.cloudinary.cloud_name');
        $this->apiKey    = config('services.cloudinary.api_key');
        $this->apiSecret = config('services.cloudinary.api_secret');
        $this->folder    = config('services.cloudinary.folder', 'workout-exercises');
    }

    /**
     * Pastikan kredensial Cloudinary sudah dikonfigurasi sebelum benar-benar
     * memanggil API-nya. Constructor sengaja tidak melempar error di sini,
     * supaya controller yang meng-inject service ini (lewat DI) tidak ikut
     * gagal dipakai untuk aksi yang sama sekali tidak menyentuh Cloudinary
     * (mis. hanya menampilkan/menyimpan data teks) saat env belum diisi.
     */
    private function ensureConfigured(): void
    {
        if (! $this->cloudName || ! $this->apiKey || ! $this->apiSecret) {
            throw new RuntimeException(
                'Cloudinary belum dikonfigurasi. Pastikan CLOUDINARY_CLOUD_NAME, '
                . 'CLOUDINARY_API_KEY, dan CLOUDINARY_API_SECRET sudah diisi di file .env.'
            );
        }
    }

    /**
     * Upload video latihan ke Cloudinary.
     *
     * @return array ['url' => string secure_url, 'public_id' => string]
     */
    public function uploadVideo(UploadedFile $file): array
    {
        $this->ensureConfigured();

        $timestamp = time();

        // Hanya parameter yang dikirim ke Cloudinary (selain file, api_key, signature)
        // yang ikut ditandatangani.
        $signedParams = [
            'folder'    => $this->folder,
            'timestamp' => $timestamp,
        ];

        $response = Http::attach(
            'file',
            fopen($file->getRealPath(), 'r'),
            $file->getClientOriginalName()
        )->post("https://api.cloudinary.com/v1_1/{$this->cloudName}/video/upload", [
            'api_key'   => $this->apiKey,
            'timestamp' => $timestamp,
            'folder'    => $this->folder,
            'signature' => $this->generateSignature($signedParams),
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('Gagal mengunggah video ke Cloudinary: ' . $response->body());
        }

        $data = $response->json();

        return [
            'url'       => $data['secure_url'],
            'public_id' => $data['public_id'],
        ];
    }

    /**
     * Hapus video dari Cloudinary berdasarkan public_id (mis. saat diganti/dihapus).
     */
    public function deleteVideo(string $publicId): void
    {
        $this->ensureConfigured();

        $timestamp = time();

        $signedParams = [
            'public_id' => $publicId,
            'timestamp' => $timestamp,
        ];

        Http::asForm()->post("https://api.cloudinary.com/v1_1/{$this->cloudName}/video/destroy", [
            'public_id' => $publicId,
            'api_key'   => $this->apiKey,
            'timestamp' => $timestamp,
            'signature' => $this->generateSignature($signedParams),
        ]);
        // Tidak throw error jika video sudah tidak ada di Cloudinary
    }

    /**
     * Upload gambar (mis. foto profil) ke Cloudinary.
     *
     * @return array ['url' => string secure_url, 'public_id' => string]
     */
    public function uploadImage(UploadedFile $file, ?string $folder = null): array
    {
        $this->ensureConfigured();

        $folder = $folder ?? $this->folder;
        $timestamp = time();

        $signedParams = [
            'folder'    => $folder,
            'timestamp' => $timestamp,
        ];

        $response = Http::attach(
            'file',
            fopen($file->getRealPath(), 'r'),
            $file->getClientOriginalName()
        )->post("https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload", [
            'api_key'   => $this->apiKey,
            'timestamp' => $timestamp,
            'folder'    => $folder,
            'signature' => $this->generateSignature($signedParams),
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('Gagal mengunggah gambar ke Cloudinary: ' . $response->body());
        }

        $data = $response->json();

        return [
            'url'       => $data['secure_url'],
            'public_id' => $data['public_id'],
        ];
    }

    /**
     * Hapus gambar dari Cloudinary berdasarkan public_id (mis. saat foto profil diganti/dihapus).
     */
    public function deleteImage(string $publicId): void
    {
        $this->ensureConfigured();

        $timestamp = time();

        $signedParams = [
            'public_id' => $publicId,
            'timestamp' => $timestamp,
        ];

        Http::asForm()->post("https://api.cloudinary.com/v1_1/{$this->cloudName}/image/destroy", [
            'public_id' => $publicId,
            'api_key'   => $this->apiKey,
            'timestamp' => $timestamp,
            'signature' => $this->generateSignature($signedParams),
        ]);
        // Tidak throw error jika gambar sudah tidak ada di Cloudinary
    }

    /**
     * Signature Cloudinary: sha1("param1=val1&param2=val2..." + api_secret),
     * parameter diurutkan alfabetis, tanpa api_key/file/signature/resource_type.
     */
    private function generateSignature(array $params): string
    {
        ksort($params);

        $paramString = collect($params)
            ->map(fn ($value, $key) => "{$key}={$value}")
            ->implode('&');

        return sha1($paramString . $this->apiSecret);
    }
}
