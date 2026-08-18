<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;
use Telegram\Bot\Objects\WebhookInfo;
use Throwable;

class TelegramWebhookCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:webhook 
                            {--info : Cek status dan error log webhook dari server Telegram}
                            {--set : Daftarkan webhook otomatis menggunakan APP_URL}
                            {--delete : Hapus webhook yang terdaftar di Telegram}
                            {--url= : Tentukan URL webhook kustom (opsional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kelola dan periksa status Webhook Bot Telegram (Info, Set, Delete)';

    /**
     * Execute the console command.
     */
    public function handle(TelegramService $telegramService): int
    {
        if (! $telegramService->isConfigured()) {
            $this->error('Telegram Bot belum dikonfigurasi. Silakan lengkapi TELEGRAM_BOT_TOKEN di .env atau panel Pengaturan.');

            return self::FAILURE;
        }

        $api = $telegramService->getApi();

        // 1. Opsi Hapus Webhook
        if ($this->option('delete')) {
            try {
                $api->deleteWebhook();
                $this->info('✅ Webhook Telegram berhasil dihapus.');
            } catch (Throwable $e) {
                $this->error('Gagal menghapus webhook: '.$e->getMessage());

                return self::FAILURE;
            }

            return self::SUCCESS;
        }

        // 2. Opsi Daftarkan Webhook
        if ($this->option('set') || $this->option('url')) {
            $targetUrl = $this->option('url') ?: rtrim(config('app.url'), '/').'/api/telegram/webhook';

            if (! str_starts_with($targetUrl, 'https://')) {
                $this->warn('⚠️ Peringatan: Telegram API mewajibkan URL Webhook berprotokol HTTPS.');
            }

            try {
                $params = ['url' => $targetUrl];
                $secretToken = config('services.telegram.webhook_secret');
                if (! empty($secretToken)) {
                    $params['secret_token'] = $secretToken;
                }

                $api->setWebhook($params);
                $this->info("✅ Webhook berhasil didaftarkan ke: {$targetUrl}");
            } catch (Throwable $e) {
                $this->error('Gagal mendaftarkan webhook: '.$e->getMessage());

                return self::FAILURE;
            }
        }

        // 3. Tampilkan Info Webhook
        try {
            /** @var WebhookInfo $info */
            $info = $api->getWebhookInfo();

            $this->newLine();
            $this->info('📊 === STATUS WEBHOOK TELEGRAM SAAT INI ===');
            $this->table(
                ['Parameter', 'Nilai'],
                [
                    ['URL Terdaftar', $info->url ?: '(Belum ada / Kosong)'],
                    ['Custom Certificate', $info->hasCustomCertificate ? 'Ya' : 'Tidak'],
                    ['Pending Updates Count', $info->pendingUpdateCount ?? 0],
                    ['Max Connections', $info->maxConnections ?? 40],
                    ['Last Error Date', $info->lastErrorDate ? date('Y-m-d H:i:s', $info->lastErrorDate).' WIB' : '-'],
                    ['Last Error Message', $info->lastErrorMessage ?: 'Tidak ada error (OK)'],
                    ['IP Address Server', $info->ipAddress ?: '-'],
                ]
            );

            if (! empty($info->lastErrorMessage)) {
                $this->newLine();
                $this->warn("⚠️ Terdeteksi Error dari Server Telegram: {$info->lastErrorMessage}");
            }
        } catch (Throwable $e) {
            $this->error('Gagal mengambil info webhook: '.$e->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
