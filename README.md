# SISKA - Sistem Informasi Santri dan Kelembagaan

SISKA adalah sistem manajemen terpadu modern untuk pondok pesantren, madrasah, dan institusi pendidikan Islam. Sistem ini mencakup pengelolaan santri, akademik & rapor, presensi RFID, keuangan/SPP, penggajian guru, publikasi landing page multi-tema dengan visual editor, notifikasi bot Telegram, serta arsitektur multi-lembaga.

---

## 🛠️ Tech Stack & Ekosistem

- **Framework**: [Laravel 13](https://laravel.com) (PHP 8.4+)
- **Frontend & Reactivity**: [Livewire 4](https://livewire.laravel.com) + [Livewire Flux](https://flux.livewire.com) + [Alpine.js](https://alpinejs.dev)
- **Styling**: [Tailwind CSS v4](https://tailwindcss.com)
- **Cloud Media Storage**: [ImageKit.io](https://imagekit.io) (Upload CDN & Media Optimization API)
- **Bot & Notifikasi**: [Telegram Bot SDK](https://telegram-bot-sdk.com/docs/) (`irazasyed/telegram-bot-sdk`)
- **Autentikasi & OAuth**: [Laravel Fortify](https://laravel.com/docs/fortify) (2FA TOTP, Passkeys) + [Laravel Socialite](https://laravel.com/docs/socialite) (Google OAuth)
- **Database**: MySQL / SQLite
- **Testing**: [Pest PHP v4](https://pestphp.com)

---

## 🌟 Fitur Unggulan Terbaru

### 1. 🤖 Integrasi Bot Telegram (Notifikasi & Perintah Interaktif)
- **Notifikasi Otomatis ke Admin**:
  - **Pendaftaran Guru Baru**: Notifikasi instan dengan tombol inline **`[ ✅ Konfirmasi & Aktifkan ]`** untuk aktivasi 1-klik langsung dari chat Telegram.
  - **Pendaftaran Calon Santri Baru**: Detail nama, lembaga, kelas, dan nomor kontak wali santri saat mendaftar online (`/daftar`).
  - **Input Nilai oleh Guru**: Notifikasi mata pelajaran, nama guru, semester, santri, dan nilai yang disimpan.
  - **Notifikasi Login Pengguna**: Notifikasi saat user (Guru, Santri, Operator, Kepala Madrasah, Keuangan) berhasil masuk ke sistem.
- **Perintah Interaktif Bot (*Commands*)**:
  - `/start` : Panduan bot dan informasi sistem SISKA.
  - `/online` : Menampilkan daftar pengguna yang sedang aktif/login pada sistem.
  - `/akademik` : Status guru yang sudah input nilai vs yang belum input nilai pada semester berjalan (`Format: 1. Nama - ✅ Sudah Input / ⏳ Belum Input`).
  - `/staff` : Daftar seluruh staf lembaga (Nama, Email, Jabatan / Role, dan Wali Kelas).
- **Pengujian di Localhost**:
  Jalankan `php artisan telegram:poll` untuk menerima update dan klik tombol di localhost tanpa perlu setup domain/ngrok.

### 2. 🎨 Visual Inline Content Editor (CMS Publik)
- Mode edit visual inline langsung di seluruh halaman website publik (Beranda, Program, Galeri, Tentang Kami, Kontak).
- Kemudahan mengubah teks judul, deskripsi, hingga mengganti foto banner latar belakang secara instan tanpa masuk ke panel admin teknis.

### 3. 🗂️ Drawer / Slider Flyout CRUD (No Modals)
- Seluruh form tambah, ubah, dan detail data master (Guru, Santri, Kelas, Mapel, Jadwal, Tagihan, dll.) menggunakan drawer/slider flyout di sisi kanan layar untuk pengalaman UX yang cepat, konsisten, dan rapi.

### 4. ☁️ Media Cloud Storage via ImageKit.io API
- Seluruh berkas unggahan (foto profil guru/santri, logo lembaga, favicon, dokumen lampiran, dan foto kegiatan galeri) diunggah dan dioptimasi secara otomatis melalui ImageKit.io API.

### 5. 🔐 Google OAuth & Konfirmasi Guru Baru
- Guru dapat mendaftar dan masuk secara praktis menggunakan akun Google.
- Akun guru baru yang mendaftar via Google otomatis berstatus *pending* (*tidak aktif*) dan wajib dikonfirmasi oleh Administrator sebelum dapat mengakses dashboard.

### 6. 🔔 Real-Time Notification TopBar
- Panel notifikasi reaktif dengan 4 tab filter (*Semua, Guru, Santri, Pesan*).
- Status notifikasi berubah menjadi terbaca (*read*) secara instan tanpa *reload* halaman saat kartu diklik atau saat tindakan konfirmasi dilakukan.

### 7. ⚙️ Halaman Pengaturan (Settings) Terpadu
- Tampilan grid responsif multi-kolom untuk pengaturan identitas lembaga, SEO, cutoff payroll, WhatsApp gateway, dan integrasi Bot Telegram.
- Tab Profil dan Keamanan (2FA, Ubah Password) digabung dalam satu antarmuka yang ringkas dan aman.

---

## 🚀 Panduan Instalasi (Local Development)

### 1. Prasyarat
- PHP >= 8.4 (ekstensi: `pdo`, `mbstring`, `openssl`, `curl`, `gd` / `imagick`)
- Composer
- Node.js (v20+) & npm
- MySQL / MariaDB

### 2. Langkah Setup Cepat
```bash
# Clone repository
git clone <repository_url> siska
cd siska

# Jalankan skrip setup otomatis
composer setup
```

Atau instalasi manual:
```bash
# Install dependensi PHP & Node
composer install
npm install

# Konfigurasi environment
cp .env.example .env
php artisan key:generate

# Migrasi database
php artisan migrate

# Build frontend assets
npm run build
```

### 3. Menjalankan Server Development
```bash
# Menjalankan server aplikasi & Vite secara bersamaan
composer run dev

# (Opsional) Menjalankan listener Telegram Bot di localhost
php artisan telegram:poll
```

---

## ⚙️ Konfigurasi Environment Tambahan (`.env`)

Selain konfigurasi dasar Laravel, lengkapi variabel berikut untuk mengaktifkan seluruh fitur cloud & notifikasi:

```dotenv
# ImageKit.io (Media & Document Storage)
IMAGEKIT_PUBLIC_KEY=your_imagekit_public_key
IMAGEKIT_PRIVATE_KEY=your_imagekit_private_key
IMAGEKIT_URL_ENDPOINT=https://ik.imagekit.io/your_id

# Google OAuth Login
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"

# Telegram Bot Integration
TELEGRAM_BOT_TOKEN=123456789:ABCDefghIJKLmnOPQRstUVwxYZ...
TELEGRAM_ADMIN_CHAT_ID=987654321
TELEGRAM_WEBHOOK_SECRET=your_optional_webhook_secret

# Hardware RFID Endpoint (Opsional)
RFID_DEVICE_KEY=your_random_64_hex_secret
```

> **Tips Telegram**: Pengaturan Bot Token & Chat ID juga dapat dikonfigurasi dan diuji secara visual melalui menu **Pengaturan → Pengaturan Lembaga → Integrasi Bot Telegram**.

---

## 🔒 Konfigurasi Keamanan (Production vs Local)

| Variabel `.env` | Nilai di Local | Rekomendasi di Production | Prioritas |
|---|---|---|---|
| `APP_ENV` | `local` | `production` | 🔴 Kritis |
| `APP_DEBUG` | `true` | `false` | 🔴 Kritis |
| `DB_USERNAME` | `root` | User terbatas (misal: `siska_app`) | 🔴 Kritis |
| `DB_PASSWORD` | Bebas / kosong | Password acak kuat (min. 20 karakter) | 🔴 Kritis |
| `SESSION_ENCRYPT` | `false` | `true` | 🟠 Tinggi |
| `SESSION_SECURE_COOKIE` | `false` | `true` (Wajib aktif jika HTTPS) | 🔴 Kritis |
| `SESSION_SAME_SITE` | `lax` | `strict` | 🟠 Tinggi |
| `TRUSTED_PROXIES` | `*` | IP spesifik Reverse Proxy / Load Balancer | 🟡 Sedang |
| `LOG_LEVEL` | `debug` | `warning` / `error` | 🟠 Tinggi |

---

## 🚢 Prosedur Deployment ke Production

```bash
# 1. Update kode sumber
git pull origin main

# 2. Update dependensi tanpa dev tools
composer install --no-dev --optimize-autoloader

# 3. Jalankan migrasi database
php artisan migrate --force

# 4. Build asset frontend
npm ci
npm run build

# 5. Optimasi cache Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 6. Pasang Webhook Telegram (Cukup 1x di Production)
# Buka URL berikut di browser:
# https://api.telegram.org/bot<TOKEN_BOT>/setWebhook?url=https://domain-anda.com/api/telegram/webhook
```

---

## 🧪 Pengujian (Automated Testing)

Seluruh modul dan alur logika diuji secara komprehensif menggunakan **Pest PHP**:

```bash
# Menjalankan seluruh test suite
php artisan test --compact

# Menjalankan test khusus Telegram Service & Webhook
php artisan test --compact tests/Feature/Services/TelegramServiceTest.php tests/Feature/Telegram/TelegramWebhookTest.php
```