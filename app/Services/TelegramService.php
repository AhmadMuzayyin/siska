<?php

namespace App\Services;

use App\Enums\GuruStatus;
use App\Enums\UserRole;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Santri;
use App\Models\Semester;
use App\Models\Setting;
use App\Models\User;
use App\Services\Telegram\LaravelHttpClientHandler;
use App\Telegram\Commands\AkademikCommand;
use App\Telegram\Commands\OnlineCommand;
use App\Telegram\Commands\StaffCommand;
use App\Telegram\Commands\StartCommand;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;
use Throwable;

class TelegramService
{
    /**
     * Dapatkan Bot Token aktif dari Setting database atau config.
     */
    public function getBotToken(): ?string
    {
        try {
            $settingToken = Setting::query()->value('telegram_bot_token');
            if (! empty($settingToken)) {
                return $settingToken;
            }
        } catch (Throwable) {
            // Ignore if setting table is not available
        }

        $configToken = config('telegram.bots.mybot.token') ?: config('services.telegram.bot_token');

        return ! empty($configToken) && $configToken !== 'YOUR-BOT-TOKEN' ? $configToken : null;
    }

    /**
     * Dapatkan Chat ID Admin tujuan notifikasi.
     */
    public function getAdminChatId(): ?string
    {
        try {
            $settingChatId = Setting::query()->value('telegram_admin_chat_id');
            if (! empty($settingChatId)) {
                return $settingChatId;
            }
        } catch (Throwable) {
            // Ignore
        }

        return config('services.telegram.chat_id') ?: null;
    }

    /**
     * Cek apakah bot Telegram telah dikonfigurasi.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->getBotToken()) && ! empty($this->getAdminChatId());
    }

    /**
     * Dapatkan instance API Telegram SDK.
     */
    public function getApi(): Api
    {
        $token = $this->getBotToken();

        return new Api($token, false, new LaravelHttpClientHandler);
    }

    /**
     * Kirim pesan teks ke Telegram dengan dukungan Parse Mode HTML & Inline Keyboard.
     *
     * @param  array<int, array<int, array<string, string>>>|null  $inlineKeyboard
     */
    public function sendMessage(string $text, ?array $inlineKeyboard = null, ?string $chatId = null): ?Message
    {
        $targetChatId = $chatId ?: $this->getAdminChatId();
        $token = $this->getBotToken();

        if (empty($targetChatId) || empty($token)) {
            return null;
        }

        try {
            $params = [
                'chat_id' => $targetChatId,
                'text' => $text,
                'parse_mode' => 'HTML',
            ];

            if (! empty($inlineKeyboard)) {
                $params['reply_markup'] = Keyboard::make([
                    'inline_keyboard' => $inlineKeyboard,
                ]);
            }

            return $this->getApi()->sendMessage($params);
        } catch (Throwable $e) {
            Log::warning('Gagal mengirim pesan Telegram: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Ubah teks pesan yang sudah terkirim (misal setelah tombol konfirmasi diklik).
     *
     * @param  array<int, array<int, array<string, string>>>|null  $inlineKeyboard
     */
    public function editMessageText(int|string $chatId, int $messageId, string $text, ?array $inlineKeyboard = null): ?Message
    {
        $token = $this->getBotToken();
        if (empty($token)) {
            return null;
        }

        try {
            $params = [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'text' => $text,
                'parse_mode' => 'HTML',
            ];

            if ($inlineKeyboard !== null) {
                $params['reply_markup'] = Keyboard::make([
                    'inline_keyboard' => $inlineKeyboard,
                ]);
            }

            /** @var Message $result */
            $result = $this->getApi()->editMessageText($params);

            return $result;
        } catch (Throwable $e) {
            Log::warning('Gagal mengedit pesan Telegram: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Berikan respon alert pop-up pada interaksi inline keyboard Telegram.
     */
    public function answerCallbackQuery(string $callbackQueryId, string $text, bool $showAlert = false): bool
    {
        $token = $this->getBotToken();
        if (empty($token)) {
            return false;
        }

        try {
            $this->getApi()->answerCallbackQuery([
                'callback_query_id' => $callbackQueryId,
                'text' => $text,
                'show_alert' => $showAlert,
            ]);

            return true;
        } catch (Throwable $e) {
            Log::warning('Gagal menjawab callback query Telegram: '.$e->getMessage());

            return false;
        }
    }

    /**
     * 1. Notifikasi Pendaftaran Guru Baru (dengan tombol konfirmasi langsung).
     */
    public function sendNewGuruNotification(User $user, Guru $guru): ?Message
    {
        $time = now()->translatedFormat('d F Y, H:i');
        $authMethod = $user->account_type?->value === 'google' ? 'Google OAuth' : 'Registrasi Akun';
        $dashboardUrl = route('kepegawaian.guru');

        $text = "👨‍🏫 <b>PENDAFTARAN GURU BARU</b>\n"
            ."━━━━━━━━━━━━━━━━━━━━\n"
            ."• <b>Nama:</b> {$user->name}\n"
            ."• <b>Email:</b> {$user->email}\n"
            ."• <b>Metode:</b> {$authMethod}\n"
            ."• <b>Status:</b> ⏳ Menunggu Konfirmasi\n"
            ."• <b>Waktu:</b> {$time} WIB\n"
            ."━━━━━━━━━━━━━━━━━━━━\n"
            .'<i>Silakan klik tombol di bawah untuk mengaktifkan akun guru:</i>';

        $keyboard = [
            [
                ['text' => '✅ Konfirmasi & Aktifkan', 'callback_data' => 'confirm_guru:'.$guru->id],
            ],
            [
                ['text' => '🌐 Buka di Dashboard Web', 'url' => $dashboardUrl],
            ],
        ];

        return $this->sendMessage($text, $keyboard);
    }

    /**
     * 2. Notifikasi Pendaftaran Calon Santri Baru.
     */
    public function sendNewSantriNotification(Santri $santri): ?Message
    {
        $time = now()->translatedFormat('d F Y, H:i');
        $lembagaName = $santri->lembaga?->nama ?? '-';
        $kelasName = $santri->kelas?->nama ?? 'Belum ditentukan';

        $text = "🎒 <b>PENDAFTARAN CALON SANTRI BARU</b>\n"
            ."━━━━━━━━━━━━━━━━━━━━\n"
            ."• <b>Nama Lengkap:</b> {$santri->nama_lengkap}\n"
            ."• <b>Lembaga:</b> {$lembagaName}\n"
            ."• <b>Pilihan Kelas:</b> {$kelasName}\n"
            ."• <b>Nomor Induk / NIS:</b> {$santri->noinduk}\n"
            ."• <b>No. HP Wali:</b> {$santri->telepon_wali}\n"
            ."• <b>Status:</b> ⏳ Menunggu Persetujuan\n"
            ."• <b>Waktu:</b> {$time} WIB";

        return $this->sendMessage($text);
    }

    /**
     * 3. Notifikasi Input Nilai oleh Guru.
     */
    public function sendGradeInputNotification(User $guruUser, Mapel $mapel, Santri $santri, Semester $semester, int $nilai): ?Message
    {
        $time = now()->translatedFormat('d F Y, H:i');
        $semesterInfo = ($semester->tahunAkademik?->nama ?? 'Tahun').' ('.ucfirst($semester->tipe?->value ?? '').')';

        $text = "📝 <b>INPUT NILAI SANTRI OLEH GURU</b>\n"
            ."━━━━━━━━━━━━━━━━━━━━\n"
            ."• <b>Guru:</b> {$guruUser->name}\n"
            ."• <b>Mata Pelajaran:</b> {$mapel->nama}\n"
            ."• <b>Santri:</b> {$santri->nama_lengkap}\n"
            ."• <b>Semester:</b> {$semesterInfo}\n"
            ."• <b>Nilai Disimpan:</b> <b>{$nilai}</b>\n"
            ."• <b>Waktu:</b> {$time} WIB";

        return $this->sendMessage($text);
    }

    /**
     * 4. Notifikasi User Login (khusus peran selain Admin).
     */
    public function sendUserLoginNotification(User $user, ?string $ip = null, ?string $userAgent = null): ?Message
    {
        if ($user->role === UserRole::Admin) {
            return null;
        }

        $time = now()->translatedFormat('d F Y, H:i');
        $roleLabel = match ($user->role) {
            UserRole::Guru => 'Guru / Pengajar',
            UserRole::Santri => 'Santri / Wali Santri',
            UserRole::Operator => 'Operator Sekolah',
            UserRole::KepalaMadrasah => 'Kepala Madrasah',
            UserRole::Keuangan => 'Staff Keuangan',
            default => strtoupper((string) ($user->role instanceof UserRole ? $user->role->value : $user->role)),
        };

        $ipInfo = $ip ?: request()->ip() ?: 'Unknown IP';

        $text = "🔐 <b>NOTIFIKASI LOGIN PENGGUNA</b>\n"
            ."━━━━━━━━━━━━━━━━━━━━\n"
            ."• <b>Nama:</b> {$user->name}\n"
            ."• <b>Email / ID:</b> {$user->email}\n"
            ."• <b>Peran (Role):</b> <code>{$roleLabel}</code>\n"
            ."• <b>IP Address:</b> <code>{$ipInfo}</code>\n"
            ."• <b>Waktu:</b> {$time} WIB";

        return $this->sendMessage($text);
    }

    /**
     * Proses pembaruan update (Command / Callback query) dari Telegram Webhook atau Long Polling.
     */
    public function processUpdate(Update $update): void
    {
        // 1. Handle Callback Query (e.g. confirm_guru:12)
        if ($update->has('callback_query')) {
            $callbackQuery = $update->getCallbackQuery();
            $callbackId = (string) ($callbackQuery->get('id') ?: $callbackQuery->id);
            $data = (string) ($callbackQuery->get('data') ?: $callbackQuery->data);
            $message = $callbackQuery->get('message') ?: $callbackQuery->message;
            $chat = $message ? ($message->get('chat') ?: $message->chat) : null;
            $chatId = $chat ? ($chat->get('id') ?: $chat->id) : null;
            $messageId = $message ? ($message->get('message_id') ?: $message->message_id) : null;

            Log::info('Telegram Processing Callback Query', [
                'callback_id' => $callbackId,
                'data' => $data,
                'chat_id' => $chatId,
                'message_id' => $messageId,
            ]);

            if (str_starts_with($data, 'confirm_guru:')) {
                $guruId = (int) substr($data, strlen('confirm_guru:'));
                $guru = Guru::query()->with('user')->find($guruId);

                if ($guru) {
                    $guru->update([
                        'status' => GuruStatus::Aktif,
                        'notification_read_at' => now(),
                    ]);

                    $teacherName = $guru->user?->name ?? "Guru #{$guru->id}";

                    $this->answerCallbackQuery(
                        $callbackId,
                        "✅ Sukses! Akun Guru {$teacherName} telah diaktifkan.",
                        showAlert: true
                    );

                    if ($chatId && $messageId) {
                        $confirmedTime = now()->translatedFormat('d F Y, H:i');
                        $updatedText = "👨‍🏫 <b>PENDAFTARAN GURU TELAH DIKONFIRMASI</b>\n"
                            ."━━━━━━━━━━━━━━━━━━━━\n"
                            ."• <b>Nama:</b> {$teacherName}\n"
                            ."• <b>Email:</b> {$guru->user?->email}\n"
                            ."• <b>Status:</b> ✅ <b>AKTIF (Telah Disetujui)</b>\n"
                            ."• <b>Dikonfirmasi:</b> {$confirmedTime} WIB\n"
                            ."━━━━━━━━━━━━━━━━━━━━\n"
                            .'<i>Akun guru telah aktif dan dapat langsung masuk ke portal.</i>';

                        $this->editMessageText($chatId, (int) $messageId, $updatedText, []);
                    }
                } else {
                    $this->answerCallbackQuery($callbackId, 'Data guru tidak ditemukan.', showAlert: true);
                }
            }

            return;
        }

        // 2. Handle Message Text Commands (/start, /online, /akademik, /staff)
        if ($update->has('message')) {
            $msg = $update->getMessage();
            $text = trim((string) $msg->get('text'));

            $commandMap = [
                '/start' => StartCommand::class,
                '/online' => OnlineCommand::class,
                '/akademik' => AkademikCommand::class,
                '/staff' => StaffCommand::class,
            ];

            $cmdName = explode(' ', $text)[0];
            $cmdBase = explode('@', $cmdName)[0];

            if (isset($commandMap[$cmdBase])) {
                $api = $this->getApi();
                $commandClass = $commandMap[$cmdBase];
                /** @var Command $commandInstance */
                $commandInstance = new $commandClass;
                $commandInstance->make($api, $update, []);

                return;
            }

            if (! empty(config('telegram.bots.mybot.token'))) {
                Telegram::commandsHandler(true);
            }
        }
    }
}
