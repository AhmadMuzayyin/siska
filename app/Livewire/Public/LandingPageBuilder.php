<?php

namespace App\Livewire\Public;

use App\Enums\UserRole;
use App\Models\Setting;
use App\Services\SettingService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Component;

class LandingPageBuilder extends Component
{
    public bool $isEditMode = false;

    public string $activeTab = 'hero';

    public string $theme = 'default';

    /**
     * @var array<string, mixed>
     */
    public array $content = [];

    // Quick Image Modal State
    public string $editingImageField = '';

    public string $editingImageUrl = '';

    public string $editingImageTitle = '';

    // Quick Text Modal State
    public string $editingTextField = '';

    public string $editingTextValue = '';

    public string $editingTextLabel = '';

    /**
     * Curated Islamic Educational Preset Backgrounds & Photos
     *
     * @var array<int, array{title: string, url: string, category: string}>
     */
    public array $presetImages = [
        [
            'title' => 'Santriwati Mengaji Al-Qur\'an',
            'url' => 'https://images.unsplash.com/photo-1585036156171-384164a8c675?w=1600&q=80&auto=format&fit=crop',
            'category' => 'Santri & Mengaji',
        ],
        [
            'title' => 'Santriwan Belajar Kitab & Qur\'an',
            'url' => 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=1600&q=80&auto=format&fit=crop',
            'category' => 'Santri & Mengaji',
        ],
        [
            'title' => 'Suasana Kelas Halaqah Belajar',
            'url' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=1600&q=80&auto=format&fit=crop',
            'category' => 'Kelas & Fasilitas',
        ],
        [
            'title' => 'Ruang Belajar & Perpustakaan',
            'url' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?w=1600&q=80&auto=format&fit=crop',
            'category' => 'Kelas & Fasilitas',
        ],
        [
            'title' => 'Halaqah Tilawati & Munaqosyah',
            'url' => 'https://images.unsplash.com/photo-1609220136736-443140cffec6?w=1600&q=80&auto=format&fit=crop',
            'category' => 'Kegiatan',
        ],
        [
            'title' => 'Pembinaan Karakter Santri',
            'url' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?w=1600&q=80&auto=format&fit=crop',
            'category' => 'Kegiatan',
        ],
        [
            'title' => 'Gedung Madrasah & Pesantren',
            'url' => 'https://images.unsplash.com/photo-1591604466107-ec97de577aff?w=1600&q=80&auto=format&fit=crop',
            'category' => 'Gedung',
        ],
        [
            'title' => 'Masjid & Arsitektur Islam Hijau',
            'url' => 'https://images.unsplash.com/photo-1564769625905-50e93615e769?w=1600&q=80&auto=format&fit=crop',
            'category' => 'Masjid',
        ],
    ];

    public function mount(): void
    {
        $user = auth()->user();
        if (! $user || $user->role !== UserRole::Admin) {
            return;
        }

        $setting = app(SettingService::class)->get();
        $this->theme = $setting->landing_theme ?: 'default';
        $this->loadContent();
    }

    #[Computed]
    public function isAdmin(): bool
    {
        $user = auth()->user();

        return $user && $user->role === UserRole::Admin;
    }

    public function toggleEditMode(): void
    {
        $this->isEditMode = ! $this->isEditMode;
    }

    public function openDrawer(string $tab = 'hero'): void
    {
        $this->activeTab = $tab;
        $this->modal('landing-builder-drawer')->show();
    }

    public function loadContent(): void
    {
        $setting = app(SettingService::class)->get();
        $custom = $setting->landing_custom_content ?? [];
        $themeCustom = $custom[$this->theme] ?? [];

        $defaults = $this->getDefaultsForTheme($this->theme, $setting);

        $this->content = array_merge($defaults, $themeCustom);
    }

    /**
     * Update a single field directly from the DOM (inline contenteditable or quick edit).
     */
    public function updateSingleField(string $field, string $value): void
    {
        $user = auth()->user();
        if (! $user || $user->role !== UserRole::Admin) {
            abort(403);
        }

        $setting = Setting::query()->first();
        if (! $setting) {
            return;
        }

        $cleanValue = trim($value);
        $this->content[$field] = $cleanValue;

        $allCustom = $setting->landing_custom_content ?? [];
        if (! isset($allCustom[$this->theme]) || ! is_array($allCustom[$this->theme])) {
            $allCustom[$this->theme] = [];
        }
        $allCustom[$this->theme][$field] = $cleanValue;

        $setting->update([
            'landing_custom_content' => $allCustom,
        ]);

        Cache::forget('setting.singleton');

        Flux::toast(variant: 'success', text: __('Teks berhasil diperbarui!'));
        $this->dispatch('landing-content-updated', field: $field, value: $cleanValue);
    }

    /**
     * Open the Quick Image Modal for changing a background / illustration.
     */
    public function openImageEditor(string $field, string $title = ''): void
    {
        $user = auth()->user();
        if (! $user || $user->role !== UserRole::Admin) {
            abort(403);
        }

        $this->editingImageField = $field;
        $this->editingImageTitle = $title ?: __('Ubah Gambar / Background');
        $this->editingImageUrl = (string) ($this->content[$field] ?? '');

        $this->modal('quick-image-editor-modal')->show();
    }

    /**
     * Select a preset image URL from the presets gallery.
     */
    public function selectPresetImage(string $url): void
    {
        $this->editingImageUrl = $url;
    }

    /**
     * Save the image URL from the Quick Image Modal.
     */
    public function saveImage(): void
    {
        $user = auth()->user();
        if (! $user || $user->role !== UserRole::Admin) {
            abort(403);
        }

        if (! empty($this->editingImageField)) {
            $this->updateSingleField($this->editingImageField, $this->editingImageUrl);
        }

        $this->modal('quick-image-editor-modal')->close();
        Flux::toast(variant: 'success', text: __('Gambar/Background berhasil diperbarui!'));
    }

    /**
     * Open the Quick Text Modal for editing text in a modal popup.
     */
    public function openTextEditor(string $field, string $label = ''): void
    {
        $user = auth()->user();
        if (! $user || $user->role !== UserRole::Admin) {
            abort(403);
        }

        $this->editingTextField = $field;
        $this->editingTextLabel = $label ?: __('Edit Teks');
        $this->editingTextValue = (string) ($this->content[$field] ?? '');

        $this->modal('quick-text-editor-modal')->show();
    }

    /**
     * Save the text value from the Quick Text Modal.
     */
    public function saveQuickText(): void
    {
        $user = auth()->user();
        if (! $user || $user->role !== UserRole::Admin) {
            abort(403);
        }

        if (! empty($this->editingTextField)) {
            $this->updateSingleField($this->editingTextField, $this->editingTextValue);
        }

        $this->modal('quick-text-editor-modal')->close();
    }

    /**
     * Save all fields from the drawer form.
     */
    public function save(): void
    {
        $user = auth()->user();
        if (! $user || $user->role !== UserRole::Admin) {
            abort(403);
        }

        $setting = Setting::query()->first();
        if (! $setting) {
            return;
        }

        $allCustom = $setting->landing_custom_content ?? [];
        $allCustom[$this->theme] = $this->content;

        $setting->update([
            'landing_custom_content' => $allCustom,
        ]);

        Cache::forget('setting.singleton');

        $this->modal('landing-builder-drawer')->close();
        Flux::toast(variant: 'success', text: __('Perubahan landing page berhasil disimpan!'));

        $this->dispatch('landing-content-updated');
    }

    public function resetToDefault(): void
    {
        $user = auth()->user();
        if (! $user || $user->role !== UserRole::Admin) {
            abort(403);
        }

        $setting = Setting::query()->first();
        if (! $setting) {
            return;
        }

        $allCustom = $setting->landing_custom_content ?? [];
        unset($allCustom[$this->theme]);

        $setting->update([
            'landing_custom_content' => $allCustom,
        ]);

        Cache::forget('setting.singleton');
        $this->loadContent();

        $this->modal('landing-builder-drawer')->close();
        $this->modal('confirm-reset-builder-modal')->close();

        Flux::toast(variant: 'success', text: __('Konten berhasil dikembalikan ke pengaturan bawaan.'));

        $this->dispatch('landing-content-updated');
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaultsForTheme(string $theme, Setting $setting): array
    {
        $namaLembaga = $setting->lembaga ?: config('app.name', 'SISKA');

        if ($theme === 'pixigon') {
            return [
                // Beranda - Hero & About
                'hero_badge' => 'Pendidikan Al-Qur\'an & Karakter Islami',
                'hero_title' => "Membentuk Generasi\nQur'ani & Beradab",
                'hero_subtitle' => 'Pusat pembelajaran Al-Qur\'an dan pendidikan Islam berkarakter dengan metode Tilawati bersanad resmi di '.$namaLembaga.'.',
                'hero_cta_text' => 'Daftar Santri Baru',
                'hero_cta_url' => route('santri.register.form'),
                'hero_student_image_left' => 'https://images.unsplash.com/photo-1585036156171-384164a8c675?w=600&auto=format&fit=crop&q=80',
                'hero_student_image_right' => 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=600&auto=format&fit=crop&q=80',

                'about_badge' => 'TENTANG KAMI',
                'about_title' => 'Tentang Lembaga & Pendidikan Kami',
                'about_subtitle' => 'Lembaga kami mendampingi santri meraih kefasihan membaca Al-Qur\'an, kedalaman pemahaman agama, serta pembiasaan akhlakul karimah dengan metode yang mudah, menyenangkan, dan bersanad.',
                'about_facility_image' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?w=800&auto=format&fit=crop&q=80',

                'programs_badge' => 'PROGRAM UNGGULAN',
                'programs_title' => 'Pilihan Program & Kurikulum Pembelajaran',
                'programs_subtitle' => 'Kurikulum berjenjang dan terintegrasi dirancang agar setiap santri dapat belajar sesuai tahapan usia dan kemampuan.',

                'teachers_badge' => 'USTADZ & PENGAJAR',
                'teachers_title' => 'Dewan Asatidz & Ustadzah Pengajar',
                'teachers_subtitle' => 'Tenaga pendidik bersyahadah, berdedikasi tinggi, dan telaten dalam membimbing bacaan serta akhlak santri.',

                'stats_title' => 'Capaian & Kepercayaan Ummat',
                'stats_subtitle' => 'Angka nyata dedikasi kami dalam membina generasi penerus Islam.',

                'gallery_badge' => 'DOKUMENTASI KEGIATAN',
                'gallery_title' => 'Dokumentasi Kegiatan Santri',
                'gallery_subtitle' => 'Momen kebersamaan, belajar mengaji, kajian diniyah, dan prestasi santri kami.',

                'testimonials_badge' => 'TESTIMONI',
                'testimonials_title' => 'Apa Kata Wali Santri & Alumni?',
                'testimonials_subtitle' => 'Pengalaman nyata para wali santri dan alumni dalam proses belajar mengajar di lembaga kami.',

                'cta_title' => 'Daftarkan Putra-Putri Anda Menjadi Generasi Pecinta Al-Qur\'an!',
                'cta_subtitle' => 'Mari bersama-sama membimbing putra-putri kita menjadi generasi Qurani yang fasih membaca Al-Qur\'an, kokoh dalam aqidah, dan santun dalam budi pekerti.',
                'cta_button_text' => 'Daftar Santri Baru Sekarang',

                // Halaman Program
                'page_program_title' => 'Program & Kurikulum',
                'page_program_subtitle' => 'Pilihan jenjang pendidikan Al-Qur\'an dan Madrasah Diniyah terstruktur di '.$namaLembaga.'.',
                'page_program_banner_image' => 'https://images.unsplash.com/photo-1585036156171-384164a8c675?w=1400&q=80&auto=format&fit=crop',

                // Halaman Tentang Kami
                'page_about_title' => 'Tentang Kami',
                'page_about_subtitle' => 'Mengenal profil, visi misi, dan dedikasi '.$namaLembaga.' dalam membina generasi Qurani.',
                'page_about_visi' => 'Menjadi pusat pendidikan Al-Qur\'an dan ilmu keislaman yang unggul, mencetak generasi yang fasih tartil membaca Al-Qur\'an, berakhlak mulia, dan berwawasan luas.',
                'page_about_misi' => "1. Menyelenggarakan pembelajaran Al-Qur'an terstandarisasi dengan metode Tilawati bersanad.\n2. Menanamkan nilai-nilai adab, aqidah, dan fiqih ibadah praktis sejak dini.\n3. Menyediakan tata kelola kelembagaan yang transparan, modern, dan berbasis digital.",
                'page_about_history' => 'Didirikan dengan niat tulus berkhidmat kepada Al-Qur\'an dan ummat, '.$namaLembaga.' terus berikhtiar mendidik tunas-tunas Islam agar kokoh agamanya dan mulia akhlaknya.',
                'page_about_banner_image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=1400&q=80&auto=format&fit=crop',
                'page_about_building_image' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?w=800&auto=format&fit=crop&q=80',

                // Halaman Kontak
                'page_contact_title' => 'Hubungi Kami',
                'page_contact_subtitle' => 'Informasi sekretariat, lokasi, dan layanan bantuan pendaftaran santri baru.',
                'page_contact_banner_image' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?w=1400&q=80&auto=format&fit=crop',

                // Halaman Galeri
                'page_gallery_title' => 'Galeri Dokumentasi',
                'page_gallery_subtitle' => 'Arsip foto dan rekaman kegiatan santri, prestasi munaqosyah, dan aktivitas keagamaan.',
                'page_gallery_banner_image' => 'https://images.unsplash.com/photo-1609220136736-443140cffec6?w=1400&q=80&auto=format&fit=crop',
            ];
        }

        return [
            // Beranda Default
            'hero_badge' => 'Sistem Informasi Akademik Terpadu',
            'hero_title' => $namaLembaga,
            'hero_subtitle' => $setting->meta_deskripsi ?: 'Mengelola pendidikan Al-Qur\'an dan diniyah santri secara digital — akademik, presensi RFID, nilai, dan keuangan dalam satu sistem terpadu.',
            'hero_cta_primary_text' => 'Daftar Santri Baru',
            'hero_cta_secondary_text' => 'Lihat Program',
            'hero_slide_1_image' => 'https://images.unsplash.com/photo-1585036156171-384164a8c675?w=1600&q=80&auto=format&fit=crop',
            'hero_slide_2_image' => 'https://images.unsplash.com/photo-1609220136736-443140cffec6?w=1600&q=80&auto=format&fit=crop',
            'hero_slide_3_image' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?w=1600&q=80&auto=format&fit=crop',

            'why_us_badge' => 'Mengapa Memilih Kami',
            'why_us_title' => 'Komitmen Menyajikan Pendidikan Al-Qur\'an Berkualitas & Berintegritas',
            'why_us_subtitle' => 'Kami memadukan metode pembelajaran Al-Qur\'an teruji nasional dengan tata kelola akademik digital modern untuk kenyamanan santri dan kepastian perkembangan anak bagi orang tua.',
            'why_us_image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800&q=80&auto=format&fit=crop',

            'programs_badge' => 'Kurikulum & Pendidikan',
            'programs_title' => 'Program Pendidikan Unggulan',
            'programs_subtitle' => 'Program terstruktur yang dirancang untuk memandu santri dari dasar hingga khatam.',

            'stats_title' => 'Statistik Lembaga',
            'stats_subtitle' => 'Perkembangan jumlah santri, pengajar, dan kelas yang terus bertumbuh.',

            'gallery_badge' => 'Dokumentasi Kegiatan',
            'gallery_title' => 'Galeri Foto Unggulan',
            'gallery_subtitle' => 'Dokumentasi aktivitas dan momentum berharga santri lembaga kami.',

            'teachers_badge' => 'Tenaga Pendidik',
            'teachers_title' => 'Para Pengajar',
            'teachers_subtitle' => 'Ustadz dan Ustadzah kompeten yang berdedikasi mendampingi santri.',

            'testimonials_badge' => 'Testimoni',
            'testimonials_title' => 'Kata Wali Santri & Alumni',
            'testimonials_subtitle' => 'Pengalaman wali murid dan santri belajar bersama lembaga kami.',

            'faq_badge' => 'Pertanyaan Umum',
            'faq_title' => 'Pertanyaan Yang Sering Diajukan',
            'faq_subtitle' => 'Informasi penting seputar pendaftaran, kurikulum, dan sistem akademik.',

            'cta_title' => 'Tertarik Mendaftarkan Putra-Putri Anda?',
            'cta_subtitle' => 'Mari bergabung bersama keluarga besar lembaga kami untuk mencetak generasi Qurani yang berakhlak mulia dan mandiri.',
            'cta_button_text' => 'Daftar Sekarang',
            'footer_description' => $setting->meta_deskripsi ?: 'Sistem Informasi Akademik dan Kelembagaan Pondok Pesantren.',

            // Halaman Program
            'page_program_title' => 'Program Pendidikan & Kurikulum',
            'page_program_subtitle' => 'Program pembelajaran terstruktur yang memadukan pembacaan Al-Qur\'an metode Tilawati, ilmu diniyah, serta pembinaan karakter santri.',
            'page_program_banner_image' => 'https://images.unsplash.com/photo-1585036156171-384164a8c675?w=1400&q=80&auto=format&fit=crop',

            // Halaman Tentang Kami
            'page_about_title' => 'Tentang Kami',
            'page_about_subtitle' => 'Mengenal profil, visi misi, dan dedikasi '.$namaLembaga.' dalam membina generasi Qurani.',
            'page_about_visi' => 'Menjadi pusat pendidikan Al-Qur\'an dan ilmu keislaman yang unggul, mencetak generasi yang fasih tartil membaca Al-Qur\'an, berakhlak mulia, dan berwawasan luas.',
            'page_about_misi' => "1. Menyelenggarakan pembelajaran Al-Qur'an terstandarisasi dengan metode Tilawati bersanad.\n2. Menanamkan nilai-nilai adab, aqidah, dan fiqih ibadah praktis sejak dini.\n3. Menyediakan tata kelola kelembagaan yang transparan, modern, dan berbasis digital.",
            'page_about_history' => 'Didirikan dengan niat tulus berkhidmat kepada Al-Qur\'an dan ummat, '.$namaLembaga.' terus berikhtiar mendidik tunas-tunas Islam agar kokoh agamanya dan mulia akhlaknya.',
            'page_about_banner_image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=1400&q=80&auto=format&fit=crop',
            'page_about_building_image' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?w=800&q=80&auto=format&fit=crop&q=80',

            // Halaman Kontak
            'page_contact_title' => 'Hubungi Kami',
            'page_contact_subtitle' => 'Informasi sekretariat, lokasi, dan layanan bantuan pendaftaran santri baru.',
            'page_contact_banner_image' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?w=1400&q=80&auto=format&fit=crop',

            // Halaman Galeri
            'page_gallery_title' => 'Galeri & Dokumentasi Kegiatan',
            'page_gallery_subtitle' => 'Arsip foto dan rekaman kegiatan santri, prestasi munaqosyah, dan aktivitas keagamaan.',
            'page_gallery_banner_image' => 'https://images.unsplash.com/photo-1609220136736-443140cffec6?w=1400&q=80&auto=format&fit=crop',
        ];
    }

    public function render(): View
    {
        return view('livewire.public.landing-page-builder');
    }
}
