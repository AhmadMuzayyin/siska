# SISKA - Sistem Informasi Santri dan Kelembagaan

SISKA adalah sistem manajemen terpadu untuk pondok pesantren dan institusi pendidikan Islam yang mencakup pengelolaan santri, akademik, presensi RFID, keuangan/SPP, penggajian guru, publikasi landing page, serta multi-lembaga.

---

## 🛠️ Tech Stack & Ekosistem

- **Framework**: [Laravel 13](https://laravel.com) (PHP 8.4+)
- **Frontend & Reactivity**: [Livewire 4](https://livewire.laravel.com) + [Livewire Flux](https://flux.livewire.com) + [Alpine.js](https://alpinejs.dev)
- **Styling**: [Tailwind CSS v4](https://tailwindcss.com)
- **Autentikasi & Keamanan**: [Laravel Fortify v1](https://laravel.com/docs/fortify) (2FA TOTP, Password Confirmation, Rate Limiting, CSP Middleware)
- **Database**: MySQL / SQLite (Development & Testing)
- **Testing**: [Pest PHP v4](https://pestphp.com)

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

Atau lakukan instalasi manual:
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
# atau:
php artisan serve
npm run dev
```

---

## 🔒 Panduan Konfigurasi Keamanan Environment (`.env`)

Seluruh penjelasan variabel konfigurasi lingkungan (`.env`) terkait keamanan dikumpulkan dalam bagian ini untuk mempermudah audit dan deployment.

### 📊 Ringkasan Perbedaan Konfigurasi: Local vs Production

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
| `RFID_DEVICE_KEY` | *(opsional)* | Kunci rahasia SHA-256 (64 hex) | 🔴 Kritis (jika modul RFID aktif) |

---

### 📖 Penjelasan Detail Parameter Keamanan

#### 1. Mode Aplikasi (`APP_ENV` & `APP_DEBUG`)
- **`APP_ENV=production`**: Mengaktifkan seluruh proteksi internal framework dan middleware keamanan, termasuk *Content Security Policy (CSP)* ketat.
- **`APP_DEBUG=false`**: Menonaktifkan halaman detail error (Ignition/Whoops). Mencegah kebocoran stack trace, kode sumber, struktur database, kredensial koneksi, dan token environment kepada publik saat terjadi error 500.

#### 2. Keamanan Akun Database (Least Privilege)
Jangan pernah menggunakan akun `root` MySQL pada server production. Buat user khusus dengan hak akses terbatas hanya pada database `siska`:

```sql
-- Jalankan di console MySQL/MariaDB server production:
CREATE USER 'siska_app'@'localhost' IDENTIFIED BY 'GantiDenganPasswordSangatKuat123!#';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, REFERENCES ON siska.* TO 'siska_app'@'localhost';
FLUSH PRIVILEGES;
```

Kemudian sesuaikan pada `.env`:
```dotenv
DB_USERNAME=siska_app
DB_PASSWORD=GantiDenganPasswordSangatKuat123!#
```

#### 3. Keamanan Sesi Pengguna (`SESSION_*`)
- **`SESSION_ENCRYPT=true`**: Mengenkripsi seluruh muatan payload sesi sebelum disimpan ke tabel `sessions`. Melindungi data sesi dari pembacaan langsung jika database sempat diakses pihak yang tidak berhak.
- **`SESSION_SECURE_COOKIE=true`**: Memaksa browser hanya mengirim cookie sesi (`siska_session`) melalui jalur aman **HTTPS**. Mencegah pencurian sesi melalui serangan *Man-in-the-Middle (MitM)*.
- **`SESSION_SAME_SITE=strict`**: Mencegah cookie sesi dikirim pada request lintas situs (*Cross-Site Request Forgery / CSRF*).

#### 4. Proxy dan Load Balancer (`TRUSTED_PROXIES`)
Aplikasi SISKA mengimplementasikan pembatasan laju (*Rate Limiting*) berbasis alamat IP pengguna asli.
- **Di Local**: `TRUSTED_PROXIES=*` aman digunakan karena request datang dari localhost.
- **Di Production**: 
  - Jika server berada di balik Nginx Reverse Proxy, AWS ALB, atau Cloudflare, masukkan daftar IP proxy yang dipercaya (contoh: `TRUSTED_PROXIES=10.0.0.1,10.0.0.2`).
  - Jika menggunakan shared/cloud hosting di mana IP proxy dinamis, tetap gunakan `TRUSTED_PROXIES=*`.

#### 5. Pembatasan Level Log (`LOG_LEVEL`)
- **`LOG_LEVEL=warning`**: Di production, hanya mencatat event warning, error, dan critical. Mencegah log file membengkak serta menghindari pencatatan query database atau payload sensitif yang biasanya muncul pada level `debug`.

#### 6. Autentikasi Hardware RFID (`RFID_DEVICE_KEY`)
Endpoint absensi santri via alat fisik (`POST /api/rfid/scan`) dilindungi oleh middleware `VerifyRfidDeviceKey` yang memvalidasi header `X-Device-Key` menggunakan perbandingan waktu konstan (`hash_equals`) untuk mencegah *timing attack*.
- Buat kunci rahasia acak:
  ```bash
  php -r "echo bin2hex(random_bytes(32));"
  ```
- Pasang nilai tersebut pada `.env` server dan firmware/alat pembaca RFID:
  ```dotenv
  RFID_DEVICE_KEY=8f14e45fceea167a5a36dedd4bea2543...
  ```

---

## 🛡️ Fitur Keamanan Bawaan Lainnya

1. **HTTP Security Headers Middleware (`App\Http\Middleware\SecurityHeaders`)**:
   - `X-Content-Type-Options: nosniff` (Mencegah MIME-type sniffing).
   - `X-Frame-Options: SAMEORIGIN` (Mencegah serangan Clickjacking/iframe).
   - `Referrer-Policy: strict-origin-when-cross-origin`.
   - `Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=()`.
   - `Content-Security-Policy (CSP)`: Dikonfigurasi otomatis aktif pada mode `production` untuk mengamankan eksekusi script, style, font, dan frame.
2. **Two-Factor Authentication (2FA / TOTP)**:
   - Pengguna dapat mengaktifkan 2FA melalui menu **Settings > Security**.
   - Kompatibel dengan aplikasi Google Authenticator, Microsoft Authenticator, dan Authy.
   - Dilengkapi *Recovery Codes* terenkripsi.
3. **Penonaktifan Registrasi Terbuka**:
   - `Features::registration()` dinonaktifkan secara default. Pembuatan user baru dilakukan secara terkelola melalui panel **Admin > Users**.
4. **Rate Limiting**:
   - Form kontak publik (`/kontak`) dan pendaftaran santri online (`/daftar`) dibatasi 6 request per menit untuk mencegah spam bot dan serangan DoS.

---

## 🚢 Prosedur Deployment ke Production

Setiap kali melakukan deployment kode baru atau pembaruan konfigurasi di server production:

```bash
# 1. Update kode sumber
git pull origin main

# 2. Update dependensi tanpa dev tools
composer install --no-dev --optimize-autoloader

# 3. Jalankan migrasi database
php artisan migrate --force

# 4. Build asset frontend untuk production
npm ci
npm run build

# 5. Optimasi cache konfigurasi dan rute Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 6. Restart queue worker (jika menggunakan antrean background)
php artisan queue:restart
```

---

## 🧪 Pengujian (Automated Testing)

Seluruh fitur dan mekanisme keamanan telah memiliki pengujian otomatis menggunakan **Pest PHP**:

```bash
# Menjalankan seluruh test suite
php artisan test --compact

# Menjalankan khusus test pengerasan keamanan
php artisan test --compact tests/Feature/SecurityHardeningTest.php
```