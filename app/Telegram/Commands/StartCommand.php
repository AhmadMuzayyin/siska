<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    protected string $name = 'start';

    protected string $description = 'Mulai bot dan tampilkan panduan perintah';

    public function handle(): void
    {
        $text = "🤖 <b>Selamat datang di SISKA Admin Telegram Bot!</b>\n\n"
            ."Bot ini terhubung dengan <b>Sistem Informasi Sekolah & Akademik (SISKA)</b> untuk mengirimkan notifikasi aktivitas sistem secara real-time dan monitoring data lembaga.\n\n"
            ."📋 <b>Daftar Perintah yang Tersedia:</b>\n"
            ."• /start - Menampilkan panduan dan informasi bot\n"
            ."• /online - Listing pengguna yang sedang login / aktif sesi\n"
            ."• /akademik - Status guru yang sudah vs belum input nilai\n"
            ."• /staff - Listing staf lembaga (Guru, Operator, Kepala, dll)\n\n"
            .'🔔 <i>Notifikasi pendaftaran guru baru, calon santri, input nilai, dan login pengguna akan masuk otomatis ke chat ini.</i>';

        $this->replyWithMessage([
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }
}
