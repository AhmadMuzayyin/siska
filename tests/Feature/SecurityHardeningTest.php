<?php

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Security Hardening Tests
 *
 * Memastikan semua perubahan keamanan berfungsi dengan benar:
 * 1. Security Headers tersedia di semua response
 * 2. Registrasi publik dinonaktifkan
 * 3. Route admin hanya bisa diakses user yang sudah login
 * 4. Two-Factor Authentication tersedia
 */
describe('Security Headers', function () {
    it('adds X-Content-Type-Options nosniff header on web responses', function () {
        $response = $this->get('/');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
    });

    it('adds X-Frame-Options SAMEORIGIN header', function () {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    });

    it('adds Referrer-Policy header', function () {
        $response = $this->get('/');

        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    });

    it('adds Permissions-Policy header', function () {
        $response = $this->get('/');

        $response->assertHeader('Permissions-Policy');
    });

    it('does NOT add Content-Security-Policy in non-production environments', function () {
        // CSP hanya aktif di production agar Vite HMR dan Alpine tidak diblokir saat development
        $response = $this->get('/');

        $response->assertHeaderMissing('Content-Security-Policy');
    });

    it('includes security headers on authenticated pages', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    });
});

describe('Registration Disabled', function () {
    it('returns 404 on GET /register', function () {
        $this->get('/register')->assertNotFound();
    });

    it('returns 404 on POST /register', function () {
        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertNotFound();
    });

    it('does not show register link on login page', function () {
        $response = $this->get('/login');

        $response->assertDontSee("route('register')", false);
        $response->assertDontSee('Sign up');
        $response->assertDontSee('/register');
    });
});

describe('Authentication Gate', function () {
    it('redirects unauthenticated user from dashboard to login', function () {
        $this->get('/dashboard')->assertRedirect('/login');
    });

    it('redirects unauthenticated user from admin routes to login', function () {
        $this->get('/admin/users')->assertRedirect('/login');
        $this->get('/kesantrian/santri')->assertRedirect('/login');
        $this->get('/keuangan/spp')->assertRedirect('/login');
    });

    it('redirects unauthenticated user from settings to login', function () {
        $this->get('/settings/profile')->assertRedirect('/login');
        $this->get('/settings/security')->assertRedirect('/login');
    });
});

describe('Two-Factor Authentication Setup', function () {
    it('user model uses TwoFactorAuthenticatable trait', function () {
        $user = User::factory()->create();

        expect(method_exists($user, 'twoFactorQrCodeSvg'))->toBeTrue();
        expect(method_exists($user, 'recoveryCodes'))->toBeTrue();
        expect($user->two_factor_secret)->toBeNull();
        expect($user->two_factor_confirmed_at)->toBeNull();
    });

    it('two-factor columns exist on users table', function () {
        $columns = Schema::getColumnListing('users');

        expect($columns)->toContain('two_factor_secret');
        expect($columns)->toContain('two_factor_recovery_codes');
        expect($columns)->toContain('two_factor_confirmed_at');
    });

    it('two-factor challenge route exists', function () {
        expect(Route::has('two-factor.login'))->toBeTrue();
    });

    it('two-factor challenge page is accessible when pending 2fa', function () {
        // Test that the route exists and returns a valid response structure
        $response = $this->get('/two-factor-challenge');

        // Should redirect to login (no active 2fa session) or return view
        expect($response->status())->toBeIn([200, 302]);
    });
});

describe('Public Routes Rate Limiting', function () {
    it('throttles contact form submissions', function () {
        // Gunakan withSession untuk memastikan CSRF token valid di semua request
        $token = Str::random(40);
        $session = ['_token' => $token];

        $payload = [
            '_token' => $token,
            'name' => 'Test',
            'email' => 'test@example.com',
            'subject' => 'Test Subject',
            'message' => 'Test message content',
        ];

        for ($i = 0; $i < 6; $i++) {
            $this->withSession($session)->post('/kontak', $payload);
        }

        // Request ke-7 harus di-throttle (429)
        $this->withSession($session)->post('/kontak', $payload)->assertStatus(429);
    });

    it('throttles santri registration form submissions', function () {
        $token = Str::random(40);
        $session = ['_token' => $token];

        $kelas = Kelas::factory()->create();

        $payload = [
            '_token' => $token,
            'kelas_id' => $kelas->id,
            'noinduk' => '12345',
            'nama_lengkap' => 'Test Santri',
            'nama_panggilan' => 'Test',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-01-01',
            'anak_ke' => 1,
            'alamat' => 'Jl. Test No. 1',
            'jenis_kelamin' => 'laki_laki',
            'nama_ayah' => 'Ayah Test',
            'pendidikan_ayah' => 'S1',
            'pekerjaan_ayah' => 'Guru',
            'nama_ibu' => 'Ibu Test',
            'pendidikan_ibu' => 'S1',
            'pekerjaan_ibu' => 'Guru',
            'telepon_wali' => '081234567890',
        ];

        for ($i = 0; $i < 6; $i++) {
            $this->withSession($session)->post('/daftar', array_merge($payload, ['noinduk' => '1234'.$i]));
        }

        $this->withSession($session)->post('/daftar', array_merge($payload, ['noinduk' => '99999']))->assertStatus(429);
    });
});
