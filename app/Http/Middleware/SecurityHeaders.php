<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menambahkan HTTP Security Headers pada setiap response.
 *
 * Strategi CSP per environment:
 * - local/testing : Dinonaktifkan agar tidak menghalangi Vite HMR, Alpine.js, dan CDN dev tools.
 * - production    : Ketat — semua CDN dan direktif disesuaikan dengan kebutuhan nyata aplikasi.
 */
class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Header-header ini aman dan tidak mengganggu di environment manapun
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=(), payment=()');

        // CSP hanya diterapkan di production.
        // Di local/testing: Vite HMR, Alpine eval, dan CDN dev tools perlu bebas hambatan.
        if (app()->isProduction()) {
            $response->headers->set('Content-Security-Policy', $this->buildProductionCsp());
        }

        return $response;
    }

    /**
     * CSP untuk production.
     *
     * Catatan penting:
     * - 'unsafe-eval' WAJIB ada: Alpine.js v3 dan Livewire menggunakan new Function()
     *   untuk mengevaluasi direktif x-data/x-on. Ini adalah batasan arsitektur Alpine,
     *   bukan pilihan kita. Tanpa ini seluruh interaktivitas frontend akan rusak.
     * - Jika ApexCharts sudah di-bundle via npm (bukan CDN), hapus cdn.jsdelivr.net dari script-src.
     */
    private function buildProductionCsp(): string
    {
        $directives = [
            // Default: hanya izinkan resource dari domain sendiri
            "default-src 'self'",

            // Script: 'unsafe-eval' wajib untuk Alpine.js + Livewire
            // 'unsafe-inline' untuk inline event handlers yang di-generate Livewire/Alpine
            // cdn.jsdelivr.net untuk ApexCharts (jika masih pakai CDN)
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net",

            // Style: inline untuk Tailwind/Flux, Google Fonts untuk font eksternal
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",

            // Font dari Google Fonts
            "font-src 'self' https://fonts.gstatic.com data:",

            // Gambar: domain sendiri + data URI untuk avatar/inline
            "img-src 'self' data: blob:",

            // Koneksi WebSocket untuk Livewire real-time
            "connect-src 'self' wss: ws:",

            // Form hanya boleh submit ke domain sendiri
            "form-action 'self'",

            // Blokir iframe embedding dari luar
            "frame-ancestors 'self'",

            // Paksa upgrade HTTP ke HTTPS di production
            'upgrade-insecure-requests',
        ];

        return implode('; ', $directives);
    }
}
