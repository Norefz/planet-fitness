<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CloudinaryService
{
    private string $cloudName;
    private string $apiKey;
    private string $apiSecret;
    private string $folder;

    public function __construct()
    {
        $this->cloudName = config('services.cloudinary.cloud_name');
        $this->apiKey    = config('services.cloudinary.api_key');
        $this->apiSecret = config('services.cloudinary.api_secret');
        $this->folder    = config('services.cloudinary.folder', 'workout-exercises');
    }

    /**
     * Upload video latihan ke Cloudinary.
     *
     * @return array ['url' => string secure_url, 'public_id' => string]
     */
    public function uploadVideo(UploadedFile $file): array
    {
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
