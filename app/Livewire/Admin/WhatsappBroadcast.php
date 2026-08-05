<?php

namespace App\Livewire\Admin;

use App\Enums\UserRole;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Santri;
use App\Services\FonnteService;
use App\Services\SettingService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('WhatsApp Broadcast & Gateway')]
class WhatsappBroadcast extends Component
{
    public string $activeTab = 'broadcast'; // 'broadcast' | 'device'

    // Device & Token Settings
    public string $apiKeyWhatsapp = '';

    public ?string $qrCodeUrl = null;

    public ?string $deviceStatus = null;

    public ?string $deviceName = null;

    public ?string $devicePhone = null;

    public string $deviceStatusMessage = '';

    // Broadcast Form Properties
    public string $targetCategory = 'semua_santri'; // 'semua_santri' | 'semua_guru' | 'per_kelas' | 'kustom'

    public ?int $selectedKelasId = null;

    public string $customPhoneNumbers = '';

    public string $messageTemplate = "Assalamu'alaikum {{ nama }},\n\nInformasi dari {{ lembaga }}:\nMohon perhatian mengenai pembelajaran santri.\n\nTerima kasih.\n{{ tanggal }}";

    public int $delaySeconds = 2; // Default Fonnte delay in seconds

    /**
     * @var array<int, array{phone: string, message: string, nama: string}>
     */
    public array $broadcastPayloads = [];

    public bool $isReadyToSend = false;

    public function mount(SettingService $settingService): void
    {
        abort_unless(auth()->user()->role === UserRole::Admin, 403);

        $setting = $settingService->get();
        $this->apiKeyWhatsapp = (string) $setting->api_key_whatsapp;

        $this->checkDeviceStatus();
    }

    public function setTab(string $tab): void
    {
        if (in_array($tab, ['broadcast', 'device'], true)) {
            $this->activeTab = $tab;
        }
    }

    public function isConnected(): bool
    {
        $status = strtolower(trim((string) $this->deviceStatus));

        return in_array($status, ['connect', 'connected', 'terhubung']);
    }

    public function saveToken(SettingService $settingService): void
    {
        abort_unless(auth()->user()->role === UserRole::Admin, 403);

        $this->validate([
            'apiKeyWhatsapp' => 'required|string|max:255',
        ]);

        $settingService->update([
            'api_key_whatsapp' => $this->apiKeyWhatsapp,
        ]);

        Flux::toast(variant: 'success', text: __('Token API Fonnte berhasil disimpan.'));
        $this->checkDeviceStatus();
    }

    public function checkDeviceStatus(): void
    {
        if (empty($this->apiKeyWhatsapp)) {
            $this->deviceStatus = 'unconfigured';
            $this->deviceStatusMessage = 'Token API Fonnte belum diatur.';

            return;
        }

        /** @var FonnteService $fonnteService */
        $fonnteService = app(FonnteService::class);
        $result = $fonnteService->getDeviceStatus($this->apiKeyWhatsapp);

        if ($result['status']) {
            $this->deviceStatus = $result['device_status'] ?? 'connect';
            $this->deviceName = $result['name'] ?? null;
            $this->devicePhone = $result['device'] ?? null;
            $this->deviceStatusMessage = $result['message'] ?? 'Perangkat terhubung';
            $this->qrCodeUrl = null;
        } else {
            $this->deviceStatus = 'disconnected';
            $this->deviceStatusMessage = $result['message'];
        }
    }

    public function fetchQrCode(): void
    {
        if (empty($this->apiKeyWhatsapp)) {
            Flux::toast(variant: 'danger', text: __('Token Fonnte belum diatur.'));

            return;
        }

        /** @var FonnteService $fonnteService */
        $fonnteService = app(FonnteService::class);
        $result = $fonnteService->getQrCode($this->apiKeyWhatsapp);

        if ($result['status'] && ! empty($result['url'])) {
            $this->qrCodeUrl = $result['url'];
            $this->deviceStatus = 'scan_qr';
            Flux::toast(variant: 'info', text: __('Silakan pindai QR Code dengan WhatsApp Anda.'));
        } else {
            Flux::toast(variant: 'danger', text: $result['message'] ?: __('Gagal mengambil QR Code dari Fonnte.'));
        }
    }

    public function disconnectDevice(): void
    {
        if (empty($this->apiKeyWhatsapp)) {
            return;
        }

        /** @var FonnteService $fonnteService */
        $fonnteService = app(FonnteService::class);
        $result = $fonnteService->disconnectDevice($this->apiKeyWhatsapp);

        if ($result['status']) {
            $this->deviceStatus = 'disconnected';
            $this->qrCodeUrl = null;
            Flux::toast(variant: 'warning', text: __('Perangkat WhatsApp berhasil diputuskan.'));
        } else {
            Flux::toast(variant: 'danger', text: $result['message']);
        }
    }

    public function prepareBroadcast(SettingService $settingService, FonnteService $fonnteService): void
    {
        abort_unless(auth()->user()->role === UserRole::Admin, 403);

        $this->validate([
            'messageTemplate' => 'required|string|min:5',
            'targetCategory' => 'required|string|in:semua_santri,semua_guru,per_kelas,kustom',
            'delaySeconds' => 'required|integer|min:1|max:60',
            'selectedKelasId' => 'required_if:targetCategory,per_kelas|nullable|integer',
            'customPhoneNumbers' => 'required_if:targetCategory,kustom|nullable|string',
        ]);

        $setting = $settingService->get();
        $lembagaName = $setting->lembaga;
        $todayDate = Carbon::now()->locale('id')->isoFormat('D MMMM YYYY');

        $payloads = [];

        if ($this->targetCategory === 'semua_santri' || $this->targetCategory === 'per_kelas') {
            $query = Santri::query()->with('kelas');

            if ($this->targetCategory === 'per_kelas' && $this->selectedKelasId) {
                $query->where('kelas_id', $this->selectedKelasId);
            }

            /** @var Collection<int, Santri> $santris */
            $santris = $query->get();

            foreach ($santris as $santri) {
                $phone = $santri->telepon_wali;

                if (empty($phone)) {
                    continue;
                }

                $data = [
                    'nama' => $santri->nama_lengkap,
                    'nis' => $santri->nis,
                    'kelas' => $santri->kelas?->nama ?? '-',
                    'wali' => $santri->nama_wali ?? $santri->nama_lengkap,
                    'telepon' => $phone,
                    'lembaga' => $lembagaName,
                    'tanggal' => $todayDate,
                ];

                $formattedMessage = $fonnteService->replacePlaceholders($this->messageTemplate, $data);

                $payloads[] = [
                    'phone' => $phone,
                    'message' => $formattedMessage,
                    'nama' => $santri->nama_lengkap,
                ];
            }
        } elseif ($this->targetCategory === 'semua_guru') {
            /** @var Collection<int, Guru> $gurus */
            $gurus = Guru::query()->with('user')->get();

            foreach ($gurus as $guru) {
                $phone = $guru->whatsapp;

                if (empty($phone)) {
                    continue;
                }

                $data = [
                    'nama' => $guru->user?->name ?? 'Ustadz/ah',
                    'nip' => $guru->nip ?? '-',
                    'telepon' => $phone,
                    'lembaga' => $lembagaName,
                    'tanggal' => $todayDate,
                ];

                $formattedMessage = $fonnteService->replacePlaceholders($this->messageTemplate, $data);

                $payloads[] = [
                    'phone' => $phone,
                    'message' => $formattedMessage,
                    'nama' => $guru->user?->name ?? 'Ustadz/ah',
                ];
            }
        } elseif ($this->targetCategory === 'kustom') {
            $lines = explode("\n", str_replace("\r", '', $this->customPhoneNumbers));

            foreach ($lines as $line) {
                $phone = trim($line);

                if (empty($phone)) {
                    continue;
                }

                $data = [
                    'nama' => 'Penerima',
                    'telepon' => $phone,
                    'lembaga' => $lembagaName,
                    'tanggal' => $todayDate,
                ];

                $formattedMessage = $fonnteService->replacePlaceholders($this->messageTemplate, $data);

                $payloads[] = [
                    'phone' => $phone,
                    'message' => $formattedMessage,
                    'nama' => 'Penerima ('.$phone.')',
                ];
            }
        }

        if (empty($payloads)) {
            Flux::toast(variant: 'warning', text: __('Tidak ada nomor tujuan valid yang ditemukan.'));
            $this->isReadyToSend = false;

            return;
        }

        $this->broadcastPayloads = $payloads;
        $this->isReadyToSend = true;

        Flux::toast(variant: 'success', text: __('Berhasil menyiapkan :count pesan broadcast.', ['count' => count($payloads)]));
    }

    public function render(): View
    {
        return view('livewire.admin.whatsapp-broadcast', [
            'kelasList' => Kelas::query()->orderBy('nama')->get(),
        ]);
    }
}
