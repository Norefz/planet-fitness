<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ZoomService
{
    private string $accountId;
    private string $clientId;
    private string $clientSecret;
    private string $baseUrl = 'https://api.zoom.us/v2';

    public function __construct()
    {
        $this->accountId    = config('services.zoom.account_id');
        $this->clientId     = config('services.zoom.client_id');
        $this->clientSecret = config('services.zoom.client_secret');
    }

    // ── 1. Ambil access token (di-cache 55 menit) ─────────────────────────
    private function getAccessToken(): string
    {
        return Cache::remember('zoom_access_token', 55 * 60, function () {
            $response = Http::asForm()
                ->withBasicAuth($this->clientId, $this->clientSecret)
                ->post('https://zoom.us/oauth/token', [
                    'grant_type' => 'account_credentials',
                    'account_id' => $this->accountId,
                ]);

            if (! $response->successful()) {
                throw new RuntimeException('Gagal mendapatkan Zoom access token: ' . $response->body());
            }

            return $response->json('access_token');
        });
    }

    // ── 2. Buat Zoom meeting ───────────────────────────────────────────────
    /**
     * @param string $topic        Topik meeting (misal: "Konsultasi Penurunan Berat Badan")
     * @param string $startTime    Waktu mulai ISO 8601 (misal: "2026-07-14T09:00:00")
     * @param int    $duration     Durasi dalam menit
     * @param string $timezone     Timezone (default: Asia/Jakarta)
     * @return array ['meeting_id' => string, 'join_url' => string, 'start_url' => string]
     */
    public function createMeeting(
        string $topic,
        string $startTime,
        int    $duration = 60,
        string $timezone = 'Asia/Jakarta',
    ): array {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/users/me/meetings", [
                'topic'      => $topic,
                'type'       => 2,          // 2 = scheduled meeting
                'start_time' => $startTime,
                'duration'   => $duration,
                'timezone'   => $timezone,
                'settings'   => [
                    'host_video'        => true,
                    'participant_video'  => true,
                    'join_before_host'  => false,
                    'waiting_room'      => true,   // member masuk waiting room dulu
                    'auto_recording'    => 'none',
                ],
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Gagal membuat Zoom meeting: ' . $response->body());
        }

        $data = $response->json();

        return [
            'meeting_id' => (string) $data['id'],
            'join_url'   => $data['join_url'],   // link untuk MEMBER
            'start_url'  => $data['start_url'],  // link untuk MENTOR (host)
        ];
    }

    // ── 3. Hapus Zoom meeting (saat booking dibatalkan) ───────────────────
    public function deleteMeeting(string $meetingId): void
    {
        $token = $this->getAccessToken();

        Http::withToken($token)
            ->delete("{$this->baseUrl}/meetings/{$meetingId}");
        // Tidak throw error jika meeting sudah tidak ada
    }
}
