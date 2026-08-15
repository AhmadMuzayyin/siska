<?php

namespace App\Livewire\Admin;

use App\Actions\PrepareWhatsappBroadcastAction;
use App\Models\Kelas;
use App\Models\Setting;
use App\Services\FonnteService;
use App\Services\SettingService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
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
        $this->authorize('viewAny', Setting::class);

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
        $this->authorize('update', $settingService->get());

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

    public function prepareBroadcast(SettingService $settingService, PrepareWhatsappBroadcastAction $action): void
    {
        $this->authorize('update', $settingService->get());

        $this->validate([
            'messageTemplate' => 'required|string|min:5',
            'targetCategory' => 'required|string|in:semua_santri,semua_guru,per_kelas,kustom',
            'delaySeconds' => 'required|integer|min:1|max:60',
            'selectedKelasId' => 'required_if:targetCategory,per_kelas|nullable|integer',
            'customPhoneNumbers' => 'required_if:targetCategory,kustom|nullable|string',
        ]);

        $setting = $settingService->get();
        $lembagaName = (string) $setting->lembaga;
        $todayDate = Carbon::now()->locale('id')->isoFormat('D MMMM YYYY');

        $payloads = $action->handle(
            $this->messageTemplate,
            $this->targetCategory,
            $this->selectedKelasId,
            $this->customPhoneNumbers,
            $lembagaName,
            $todayDate
        );

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
