<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    private const BASE_URL = 'https://api.fonnte.com';

    public function getToken(): ?string
    {
        /** @var SettingService $settingService */
        $settingService = app(SettingService::class);
        $setting = $settingService->get();

        return $setting->api_key_whatsapp;
    }

    /**
     * @return array{status: bool, message: string, device_status?: string, qr?: string}
     */
    public function getDeviceStatus(?string $token = null): array
    {
        $token = $token ?: $this->getToken();

        if (empty($token)) {
            return [
                'status' => false,
                'message' => 'Token API Fonnte belum dikonfigurasi.',
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->timeout(10)->post(self::BASE_URL.'/device');

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'status' => (bool) ($data['status'] ?? false),
                    'message' => $data['reason'] ?? $data['message'] ?? 'Status berhasil diambil',
                    'device_status' => $data['device_status'] ?? 'unknown',
                    'device' => $data['device'] ?? null,
                    'name' => $data['name'] ?? null,
                ];
            }

            return [
                'status' => false,
                'message' => 'Gagal terhubung ke Fonnte: '.$response->body(),
            ];
        } catch (\Throwable $e) {
            Log::error('Fonnte Get Device Error: '.$e->getMessage());

            return [
                'status' => false,
                'message' => 'Terjadi kesalahan sistem: '.$e->getMessage(),
            ];
        }
    }

    /**
     * @return array{status: bool, message: string, url?: string}
     */
    public function getQrCode(?string $token = null): array
    {
        $token = $token ?: $this->getToken();

        if (empty($token)) {
            return [
                'status' => false,
                'message' => 'Token API Fonnte belum dikonfigurasi.',
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->timeout(10)->post(self::BASE_URL.'/get-qr');

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'status' => (bool) ($data['status'] ?? false),
                    'message' => $data['message'] ?? 'QR Code berhasil diambil',
                    'url' => $data['url'] ?? $data['qr'] ?? null,
                ];
            }

            return [
                'status' => false,
                'message' => 'Gagal mengambil QR Code dari Fonnte: '.$response->body(),
            ];
        } catch (\Throwable $e) {
            Log::error('Fonnte Get QR Error: '.$e->getMessage());

            return [
                'status' => false,
                'message' => 'Terjadi kesalahan sistem: '.$e->getMessage(),
            ];
        }
    }

    /**
     * @return array{status: bool, message: string}
     */
    public function disconnectDevice(?string $token = null): array
    {
        $token = $token ?: $this->getToken();

        if (empty($token)) {
            return [
                'status' => false,
                'message' => 'Token API Fonnte belum dikonfigurasi.',
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->timeout(10)->post(self::BASE_URL.'/disconnect');

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'status' => (bool) ($data['status'] ?? false),
                    'message' => $data['message'] ?? 'Perangkat berhasil diputuskan.',
                ];
            }

            return [
                'status' => false,
                'message' => 'Gagal memutuskan perangkat: '.$response->body(),
            ];
        } catch (\Throwable $e) {
            Log::error('Fonnte Disconnect Error: '.$e->getMessage());

            return [
                'status' => false,
                'message' => 'Terjadi kesalahan sistem: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Replace template placeholders format {{ namavariabel }} with actual database data.
     *
     * @param  array<string, mixed>  $data
     */
    public function replacePlaceholders(string $template, array $data): string
    {
        return preg_replace_callback('/\{\{\s*([a-zA-Z0-9_]+)\s*\}\}/', function ($matches) use ($data) {
            $key = strtolower(trim($matches[1]));

            return array_key_exists($key, $data) ? (string) $data[$key] : $matches[0];
        }, $template);
    }
}
