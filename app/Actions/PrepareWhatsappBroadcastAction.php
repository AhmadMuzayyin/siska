<?php

namespace App\Actions;

use App\Models\Guru;
use App\Models\Santri;
use App\Services\FonnteService;
use Illuminate\Database\Eloquent\Collection;

class PrepareWhatsappBroadcastAction
{
    public function __construct(
        protected FonnteService $fonnteService
    ) {}

    /**
     * Siapkan array payload pesan broadcast berdasarkan kategori target yang dipilih.
     *
     * @return array<int, array{phone: string, message: string, nama: string}>
     */
    public function handle(
        string $messageTemplate,
        string $targetCategory,
        ?int $selectedKelasId,
        string $customPhoneNumbers,
        string $lembagaName,
        string $todayDate
    ): array {
        $payloads = [];

        if ($targetCategory === 'semua_santri' || $targetCategory === 'per_kelas') {
            $query = Santri::query()->with('kelas');

            if ($targetCategory === 'per_kelas' && $selectedKelasId) {
                $query->where('kelas_id', $selectedKelasId);
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

                $formattedMessage = $this->fonnteService->replacePlaceholders($messageTemplate, $data);

                $payloads[] = [
                    'phone' => $phone,
                    'message' => $formattedMessage,
                    'nama' => $santri->nama_lengkap,
                ];
            }
        } elseif ($targetCategory === 'semua_guru') {
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

                $formattedMessage = $this->fonnteService->replacePlaceholders($messageTemplate, $data);

                $payloads[] = [
                    'phone' => $phone,
                    'message' => $formattedMessage,
                    'nama' => $guru->user?->name ?? 'Ustadz/ah',
                ];
            }
        } elseif ($targetCategory === 'kustom') {
            $lines = explode("\n", str_replace("\r", '', $customPhoneNumbers));

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

                $formattedMessage = $this->fonnteService->replacePlaceholders($messageTemplate, $data);

                $payloads[] = [
                    'phone' => $phone,
                    'message' => $formattedMessage,
                    'nama' => 'Penerima ('.$phone.')',
                ];
            }
        }

        return $payloads;
    }
}
