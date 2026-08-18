<?php

use App\Enums\UserRole;
use App\Livewire\Public\LandingPageBuilder;
use App\Models\Setting;
use App\Models\User;
use Livewire\Livewire;

describe('Landing Page Builder Feature', function () {
    beforeEach(function () {
        $this->admin = User::factory()->create(['role' => UserRole::Admin]);
        $this->guru = User::factory()->create(['role' => UserRole::Guru]);
        $this->setting = Setting::query()->first() ?? Setting::factory()->create([
            'landing_theme' => 'default',
        ]);
    });

    it('renders landing page builder toolbar for admin user on public landing page', function () {
        $this->actingAs($this->admin)
            ->get('/')
            ->assertStatus(200)
            ->assertSee('ADMIN LIVE BUILDER');
    });

    it('does not render builder toolbar for non-admin or guest users', function () {
        $this->get('/')
            ->assertStatus(200)
            ->assertDontSee('ADMIN LIVE BUILDER');

        $this->actingAs($this->guru)
            ->get('/')
            ->assertStatus(200)
            ->assertDontSee('ADMIN LIVE BUILDER');
    });

    it('allows admin to toggle edit mode in the builder', function () {
        Livewire::actingAs($this->admin)
            ->test(LandingPageBuilder::class)
            ->assertSet('isEditMode', false)
            ->call('toggleEditMode')
            ->assertSet('isEditMode', true);
    });

    it('allows admin to save customized landing content and persists it to settings', function () {
        Livewire::actingAs($this->admin)
            ->test(LandingPageBuilder::class)
            ->set('content.hero_title', 'Madrasah Hebat Bermartabat')
            ->set('content.hero_subtitle', 'Pendidikan santri masa kini dengan nilai tradisi.')
            ->set('content.programs_title', 'Program Kurikulum Santri Pilihan')
            ->call('save');

        $setting = Setting::query()->first();
        expect($setting->landing_custom_content)->toBeArray();
        expect($setting->getLandingContent('hero_title', 'Fallback', 'default'))->toBe('Madrasah Hebat Bermartabat');
        expect($setting->getLandingContent('programs_title', 'Fallback', 'default'))->toBe('Program Kurikulum Santri Pilihan');
    });

    it('displays customized content on the landing page for default and pixigon themes', function () {
        // Test Default Theme
        $this->setting->update([
            'landing_theme' => 'default',
            'landing_custom_content' => [
                'default' => [
                    'hero_title' => 'MDTA Unggulan Nusantara',
                    'cta_title' => 'Ayo Mondok di MDTA',
                ],
                'pixigon' => [
                    'hero_title' => 'Generasi Emas Qurani',
                    'cta_title' => 'Daftar Generasi Emas',
                ],
            ],
        ]);

        $this->get('/')
            ->assertStatus(200)
            ->assertSee('MDTA Unggulan Nusantara')
            ->assertSee('Ayo Mondok di MDTA');

        // Test Pixigon Theme
        $this->setting->update(['landing_theme' => 'pixigon']);

        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Generasi Emas Qurani')
            ->assertSee('Daftar Generasi Emas');
    });

    it('denies non-admin from saving landing page builder content', function () {
        Livewire::actingAs($this->guru)
            ->test(LandingPageBuilder::class)
            ->call('save')
            ->assertForbidden();
    });

    it('allows admin to update a single field inline via updateSingleField', function () {
        Livewire::actingAs($this->admin)
            ->test(LandingPageBuilder::class)
            ->call('updateSingleField', 'hero_badge', '✦ Sistem Akademik Santri Modern')
            ->call('updateSingleField', 'page_about_title', 'Tentang Pesantren & Lembaga Kami');

        $setting = Setting::query()->first();
        expect($setting->getLandingContent('hero_badge', null, 'default'))->toBe('✦ Sistem Akademik Santri Modern');
        expect($setting->getLandingContent('page_about_title', null, 'default'))->toBe('Tentang Pesantren & Lembaga Kami');
    });

    it('allows admin to open image editor modal, select preset, and save background image', function () {
        Livewire::actingAs($this->admin)
            ->test(LandingPageBuilder::class)
            ->call('openImageEditor', 'hero_slide_1_image', 'Ganti Background Hero Slider')
            ->assertSet('editingImageField', 'hero_slide_1_image')
            ->assertSet('editingImageTitle', 'Ganti Background Hero Slider')
            ->call('selectPresetImage', 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?w=1600&q=80')
            ->assertSet('editingImageUrl', 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?w=1600&q=80')
            ->call('saveImage');

        $setting = Setting::query()->first();
        expect($setting->getLandingContent('hero_slide_1_image', null, 'default'))->toBe('https://images.unsplash.com/photo-1542810634-71277d95dcbb?w=1600&q=80');
    });

    it('allows admin to open text editor modal and save quick text', function () {
        Livewire::actingAs($this->admin)
            ->test(LandingPageBuilder::class)
            ->call('openTextEditor', 'page_contact_title', 'Judul Halaman Kontak')
            ->assertSet('editingTextField', 'page_contact_title')
            ->assertSet('editingTextLabel', 'Judul Halaman Kontak')
            ->set('editingTextValue', 'Hubungi Pusat Informasi Santri')
            ->call('saveQuickText');

        $setting = Setting::query()->first();
        expect($setting->getLandingContent('page_contact_title', null, 'default'))->toBe('Hubungi Pusat Informasi Santri');
    });

    it('displays customized content on all public pages (program, tentang, kontak, galeri)', function () {
        $this->setting->update([
            'landing_theme' => 'default',
            'landing_custom_content' => [
                'default' => [
                    'page_program_title' => 'Kurikulum Tahfidz & Diniyah Terpadu',
                    'page_about_title' => 'Sejarah & Profil Lembaga Kami',
                    'page_contact_title' => 'Layanan Sekretariat & Humas',
                    'page_gallery_title' => 'Dokumentasi Santri & Prestasi',
                ],
            ],
        ]);

        $this->get(route('program'))
            ->assertStatus(200)
            ->assertSee('Kurikulum Tahfidz & Diniyah Terpadu');

        $this->get(route('about'))
            ->assertStatus(200)
            ->assertSee('Sejarah & Profil Lembaga Kami');

        $this->get(route('contact.show'))
            ->assertStatus(200)
            ->assertSee('Layanan Sekretariat & Humas');

        $this->get(route('galeri'))
            ->assertStatus(200)
            ->assertSee('Dokumentasi Santri & Prestasi');
    });

    it('allows admin to reset customized content back to default', function () {
        $this->setting->update([
            'landing_custom_content' => [
                'default' => [
                    'hero_title' => 'Customized Title',
                ],
            ],
        ]);

        Livewire::actingAs($this->admin)
            ->test(LandingPageBuilder::class)
            ->call('resetToDefault');

        $setting = Setting::query()->first();
        expect($setting->getLandingContent('hero_title', null, 'default'))->toBeNull();
    });
});
