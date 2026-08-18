<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;
use Throwable;

class TelegramPollCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:poll {--once : Run polling once and exit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Jalankan Telegram Bot Long Polling untuk menerima perintah dan klik tombol di localhost';

    /**
     * Execute the console command.
     */
    public function handle(TelegramService $telegramService): int
    {
        if (! $telegramService->isConfigured()) {
            $this->error('Telegram Bot belum dikonfigurasi. Silakan isi Bot Token & Chat ID di menu Pengaturan atau .env.');

            return self::FAILURE;
        }

        $api = $telegramService->getApi();

        $this->info('🚀 Memulai Telegram Bot Polling (Mode Localhost)...');
        $this->comment('Menghapus webhook aktif sementara agar getUpdates dapat menerima pesan...');

        try {
            $api->deleteWebhook();
            $this->info('✅ Webhook berhasil dilepas. Bot siap menerima pesan & klik tombol.');
            $this->line('Tekan CTRL+C untuk berhenti.'.PHP_EOL);
        } catch (Throwable $e) {
            $this->warn('Catatan deleteWebhook: '.$e->getMessage());
        }

        $offset = 0;

        while (true) {
            try {
                $updates = $api->getUpdates([
                    'offset' => $offset,
                    'timeout' => 5,
                ]);

                foreach ($updates as $update) {
                    $offset = $update->updateId + 1;

                    if ($update->has('callback_query')) {
                        $cb = $update->getCallbackQuery();
                        $this->info(sprintf(
                            '[%s] 🔘 Callback Query: %s dari @%s',
                            now()->format('H:i:s'),
                            $cb->getData(),
                            $cb->getFrom()->getUsername() ?: $cb->getFrom()->getFirstName()
                        ));
                    } elseif ($update->has('message')) {
                        $msg = $update->getMessage();
                        $this->info(sprintf(
                            '[%s] 💬 Pesan: "%s" dari @%s',
                            now()->format('H:i:s'),
                            $msg->getText(),
                            $msg->getFrom()->getUsername() ?: $msg->getFrom()->getFirstName()
                        ));
                    }

                    $telegramService->processUpdate($update);
                }

                if ($this->option('once')) {
                    break;
                }
            } catch (Throwable $e) {
                $this->error('Error polling: '.$e->getMessage());
                sleep(2);
            }
        }

        return self::SUCCESS;
    }
}
