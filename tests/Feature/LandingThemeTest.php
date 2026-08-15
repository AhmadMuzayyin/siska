<?php

use App\Enums\Gender;
use App\Enums\UserRole;
use App\Livewire\Auth\Login;
use App\Livewire\Public\DaftarSantri;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Index;
use App\Models\Kelas;
use App\Models\Lembaga;
use App\Models\Santri;
use App\Models\Setting;
use App\Models\User;
use Livewire\Livewire;

describe('Landing Page Themes', function () {
    it('renders the default landing theme by default', function () {
        $setting = Setting::query()->first();
        if ($setting) {
            $setting->update(['landing_theme' => 'default']);
        }

        $response = $this->get('/');
        $response->assertStatus(200);
    });

    it('renders the pixigon landing theme when selected', function () {
        $setting = Setting::query()->first();
        if ($setting) {
            $setting->update(['landing_theme' => 'pixigon']);
        }

        $this->withoutExceptionHandling();
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee(__('Membentuk Generasi'));
    });

    it('renders public subpages consistently with active theme', function () {
        $setting = Setting::query()->first();
        if ($setting) {
            $setting->update(['landing_theme' => 'pixigon']);
        }

        $this->get('/program')->assertStatus(200)->assertSee(__('Program Pendidikan'));
        $this->get('/galeri')->assertStatus(200)->assertSee(__('Galeri'));
        $this->get('/tentang')->assertStatus(200)->assertSee(__('Visi & Misi'));
        $this->get('/kontak')->assertStatus(200)->assertSee(__('Hubungi Kami'));
        $this->get('/daftar')->assertStatus(200)->assertSee(__('Identitas Calon Santri'));
    });

    it('renders santri registration page without lazy loading errors on both themes', function () {
        $setting = Setting::query()->first();

        // Default Theme
        if ($setting) {
            $setting->update(['landing_theme' => 'default']);
        }
        $this->get('/daftar')->assertStatus(200)->assertSee(__('Formulir Pendaftaran Santri Baru'));

        // Pixigon Theme
        if ($setting) {
            $setting->update(['landing_theme' => 'pixigon']);
        }
        $this->get('/daftar')->assertStatus(200)->assertSee(__('Pendaftaran Santri Baru'));
    });

    it('handles santri registration via livewire component', function () {
        $lembaga = Lembaga::factory()->create();
        $kelas = Kelas::factory()->create(['lembaga_id' => $lembaga->id]);

        Livewire::test(DaftarSantri::class)
            ->set('nama_lengkap', 'Ahmad Fauzi')
            ->set('jenis_kelamin', Gender::LakiLaki->value)
            ->set('telepon_wali', '081234567890')
            ->set('lembaga_id', $lembaga->id)
            ->set('kelas_id', $kelas->id)
            ->call('register')
            ->assertHasNoErrors()
            ->assertSet('submitted', true);

        expect(Santri::where('nama_lengkap', 'Ahmad Fauzi')->exists())->toBeTrue();
    });

    it('renders theme-specific login screen for pixigon and default themes', function () {
        $setting = Setting::query()->first();

        // Pixigon Theme: Has Slider Login & Daftar
        if ($setting) {
            $setting->update(['landing_theme' => 'pixigon']);
        }
        $pixigonLogin = $this->get('/login');
        $pixigonLogin->assertStatus(200);
        $pixigonLogin->assertSee(__('Masuk Portal'));
        $pixigonLogin->assertSee(__('Pendaftaran Santri'));

        // Default Theme: Classic Auth Layout
        if ($setting) {
            $setting->update(['landing_theme' => 'default']);
        }
        $defaultLogin = $this->get('/login');
        $defaultLogin->assertStatus(200);
        $defaultLogin->assertSee(__('Masuk ke Portal Akademik'));
    });

    it('authenticates user via livewire login component', function () {
        $user = User::factory()->create([
            'email' => 'santri@example.com',
            'password' => bcrypt('password123'),
        ]);

        Livewire::test(Login::class)
            ->set('email', 'santri@example.com')
            ->set('password', 'password123')
            ->call('login')
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);
    });

    it('allows admin to switch landing page theme in appearance settings', function () {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        Livewire::actingAs($admin)
            ->test(Appearance::class)
            ->set('landing_theme', 'pixigon')
            ->assertHasNoErrors();

        $setting = Setting::query()->first();
        expect($setting->landing_theme)->toBe('pixigon');

        // Switch back to default via unified index
        Livewire::actingAs($admin)
            ->test(Index::class)
            ->set('landing_theme', 'default')
            ->assertHasNoErrors();

        expect($setting->fresh()->landing_theme)->toBe('default');
    });

    it('validates landing theme options in appearance settings', function () {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        Livewire::actingAs($admin)
            ->test(Appearance::class)
            ->set('landing_theme', 'invalid-theme')
            ->assertHasErrors(['landing_theme']);
    });
});
