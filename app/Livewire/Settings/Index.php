<?php

namespace App\Livewire\Settings;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Enums\UserRole;
use App\Models\Setting;
use App\Services\ImageKitService;
use App\Services\SettingService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Pengaturan')]
class Index extends Component
{
    use PasswordValidationRules;
    use ProfileValidationRules;
    use WithFileUploads;

    #[Url(as: 'tab')]
    public string $tab = 'general';

    // 1. General & Lembaga Settings (Admin)
    public string $lembaga = '';

    public string $nsm = '';

    public string $alamat = '';

    public string $email_lembaga = '';

    public string $telepon = '';

    public string $meta_deskripsi = '';

    public string $meta_keyword = '';

    public int $payroll_cutoff_day = 25;

    public bool $fitur_pesan_whatsapp = false;

    public string $pesan_whatsapp = '';

    public string $google_maps_url = '';

    public mixed $logo_upload = null;

    public mixed $favicon_upload = null;

    // 2. Appearance & Landing Page Content Settings
    public string $landing_theme = 'default';

    public bool $is_input_nilai_open = true;

    public bool $is_ppdb_open = true;

    /**
     * @var array<string, mixed>
     */
    public array $content = [];

    // 3. Profile Settings
    public string $name = '';

    public string $email = '';

    // 4. Security Settings
    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(SettingService $settingService): void
    {
        $user = Auth::user();
        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
        }

        $setting = $settingService->get();
        $this->lembaga = $setting->lembaga ?? '';
        $this->nsm = (string) ($setting->nsm ?? '');
        $this->alamat = $setting->alamat ?? '';
        $this->email_lembaga = $setting->email ?? '';
        $this->telepon = $setting->telepon ?? '';
        $this->meta_deskripsi = (string) ($setting->meta_deskripsi ?? '');
        $this->meta_keyword = (string) ($setting->meta_keyword ?? '');
        $this->payroll_cutoff_day = (int) ($setting->payroll_cutoff_day ?: 25);
        $this->fitur_pesan_whatsapp = (bool) $setting->fitur_pesan_whatsapp;
        $this->pesan_whatsapp = (string) ($setting->pesan_whatsapp ?? '');
        $this->google_maps_url = (string) ($setting->google_maps_url ?? '');
        $this->landing_theme = (string) ($setting->landing_theme ?: 'default');
        $this->is_input_nilai_open = (bool) ($setting->is_input_nilai_open ?? true);
        $this->is_ppdb_open = (bool) ($setting->is_ppdb_open ?? true);

        // Load landing page content
        $allCustom = $setting->landing_custom_content ?? [];
        $themeCustom = is_array($allCustom) && isset($allCustom[$this->landing_theme]) && is_array($allCustom[$this->landing_theme])
            ? $allCustom[$this->landing_theme]
            : [];
        $this->content = array_merge($this->getDefaultPageContent($this->landing_theme, $setting), $themeCustom);

        // Set default active tab
        if ($user?->role !== UserRole::Admin && in_array($this->tab, ['general', 'pages', 'appearance'], true)) {
            $this->tab = 'profile';
        }
    }

    /**
     * @return array<string, string>
     */
    protected function getDefaultPageContent(string $theme, Setting $setting): array
    {
        $namaLembaga = $setting->lembaga ?: 'Lembaga Pendidikan Al-Qur\'an';

        if ($theme === 'pixigon') {
            return [
                'hero_badge' => 'Pendidikan Al-Qur\'an & Karakter Islami',
                'hero_title' => "Membentuk Generasi\nQur'ani & Beradab",
                'hero_subtitle' => $setting->meta_deskripsi ?: 'Pendidikan Islam terpadu dengan metode Tilawati, tahfidzul Qur\'an, kajian kitab kuning, dan pembinaan akhlak mulia.',
                'hero_cta_text' => 'Daftar Santri Baru',
                'hero_cta_url' => route('santri.register.form'),
                'about_title' => 'Tentang Lembaga & Pendidikan Kami',
                'about_subtitle' => 'Lembaga kami mendampingi santri meraih kefasihan membaca Al-Qur\'an, kedalaman pemahaman agama, serta pembiasaan akhlakul karimah.',
                'programs_title' => 'Pilihan Program & Kurikulum Pembelajaran',
                'programs_subtitle' => 'Kurikulum berjenjang dan terintegrasi dirancang agar setiap santri dapat belajar sesuai tahapan usia dan kemampuan.',
                'teachers_title' => 'Dewan Asatidz & Ustadzah Pengajar',
                'teachers_subtitle' => 'Tenaga pendidik bersyahadah, berdedikasi tinggi, dan telaten dalam membimbing bacaan serta akhlak santri.',
                'cta_title' => 'Daftarkan Putra-Putri Anda Menjadi Generasi Pecinta Al-Qur\'an!',
                'cta_subtitle' => 'Mari bersama-sama membimbing putra-putri kita menjadi generasi Qurani yang fasih membaca Al-Qur\'an dan santun budi pekerti.',
                'cta_button_text' => 'Daftar Santri Baru Sekarang',
                'page_program_title' => 'Program & Kurikulum',
                'page_program_subtitle' => 'Pilihan jenjang pendidikan Al-Qur\'an dan Madrasah Diniyah terstruktur di '.$namaLembaga.'.',
                'page_about_title' => 'Tentang Kami',
                'page_about_subtitle' => 'Mengenal profil, visi misi, dan dedikasi '.$namaLembaga.' dalam membina generasi Qurani.',
                'page_about_visi' => 'Menjadi lembaga pendidikan Al-Qur\'an terdepan yang melahirkan generasi Qurani berakhlak mulia, berprestasi, mandiri, dan berkhidmat untuk umat.',
                'page_about_misi' => "1. Menyelenggarakan pembelajaran Al-Qur'an terstandarisasi dengan metode Tilawati bersanad.\n2. Menanamkan nilai-nilai adab, aqidah, dan fiqih ibadah praktis sejak dini.\n3. Menyediakan tata kelola kelembagaan yang transparan, modern, dan berbasis digital.",
                'page_contact_title' => 'Hubungi Kami',
                'page_contact_subtitle' => 'Informasi sekretariat, lokasi, dan layanan bantuan pendaftaran santri baru.',
                'page_gallery_title' => 'Galeri Dokumentasi',
                'page_gallery_subtitle' => 'Dokumentasi aktivitas, prestasi, dan momentum berharga santri lembaga kami.',
                'hero_student_image_left' => 'https://images.unsplash.com/photo-1585036156171-384164a8c675?w=600&auto=format&fit=crop&q=80',
                'hero_student_image_right' => 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=600&auto=format&fit=crop&q=80',
                'about_facility_image' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?w=800&auto=format&fit=crop&q=80',
            ];
        }

        return [
            'hero_badge' => 'Sistem Informasi Akademik Terpadu',
            'hero_title' => $namaLembaga,
            'hero_subtitle' => $setting->meta_deskripsi ?: 'Mengelola pendidikan Al-Qur\'an dan diniyah santri secara digital — akademik, presensi RFID, nilai, dan keuangan dalam satu sistem terpadu.',
            'hero_cta_primary_text' => 'Daftar Santri Baru',
            'hero_cta_secondary_text' => 'Lihat Program',
            'why_us_badge' => 'Mengapa Memilih Kami',
            'why_us_title' => 'Komitmen Menyajikan Pendidikan Al-Qur\'an Berkualitas & Berintegritas',
            'why_us_subtitle' => 'Kami memadukan metode pembelajaran Al-Qur\'an teruji nasional dengan tata kelola akademik digital modern.',
            'programs_badge' => 'Kurikulum & Pendidikan',
            'programs_title' => 'Program Pendidikan Unggulan',
            'programs_subtitle' => 'Program terstruktur yang dirancang untuk memandu santri dari dasar hingga khatam.',
            'stats_title' => 'Statistik Lembaga',
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
            'hero_slide_1_image' => 'https://images.unsplash.com/photo-1585036156171-384164a8c675?w=1600&q=80&auto=format&fit=crop',
            'hero_slide_2_image' => 'https://images.unsplash.com/photo-1609220136736-443140cffec6?w=1600&q=80&auto=format&fit=crop',
            'hero_slide_3_image' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?w=1600&q=80&auto=format&fit=crop',
            'why_us_image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800&q=80&auto=format&fit=crop',
            'page_program_title' => 'Program Pendidikan & Kurikulum',
            'page_program_subtitle' => 'Program pembelajaran terstruktur yang memadukan pembacaan Al-Qur\'an metode Tilawati, ilmu diniyah, serta pembinaan karakter santri.',
            'page_program_banner_image' => 'https://images.unsplash.com/photo-1585036156171-384164a8c675?w=1400&q=80&auto=format&fit=crop',
            'page_about_title' => 'Tentang Kami',
            'page_about_subtitle' => 'Mengenal profil, visi misi, dan dedikasi '.$namaLembaga.' dalam membina generasi Qurani.',
            'page_about_visi' => 'Menjadi lembaga pendidikan Al-Qur\'an terdepan yang melahirkan generasi Qurani berakhlak mulia, berprestasi, mandiri, dan berkhidmat untuk umat.',
            'page_about_misi' => "1. Menyelenggarakan pembelajaran Al-Qur'an terstandarisasi dengan metode Tilawati bersanad.\n2. Menanamkan nilai-nilai adab, aqidah, dan fiqih ibadah praktis sejak dini.\n3. Menyediakan tata kelola kelembagaan yang transparan, modern, dan berbasis digital.",
            'page_about_banner_image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=1400&q=80&auto=format&fit=crop',
            'page_about_building_image' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?w=800&auto=format&fit=crop&q=80',
            'page_contact_title' => 'Hubungi Kami',
            'page_contact_subtitle' => 'Informasi sekretariat, lokasi, dan layanan bantuan pendaftaran santri baru.',
            'page_contact_banner_image' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?w=1400&q=80&auto=format&fit=crop',
            'page_gallery_title' => 'Galeri & Dokumentasi Kegiatan',
            'page_gallery_subtitle' => 'Dokumentasi aktivitas, prestasi, dan momentum berharga santri lembaga kami.',
            'page_gallery_banner_image' => 'https://images.unsplash.com/photo-1609220136736-443140cffec6?w=1400&q=80&auto=format&fit=crop',
        ];
    }

    public function saveGeneral(SettingService $settingService, ImageKitService $imageKitService): void
    {
        if (Auth::user()?->role !== UserRole::Admin) {
            abort(403);
        }

        $validated = $this->validate([
            'lembaga' => 'required|string|max:255',
            'nsm' => 'nullable|string|max:255',
            'alamat' => 'required|string',
            'email_lembaga' => 'required|email',
            'telepon' => 'required|string',
            'meta_deskripsi' => 'nullable|string',
            'meta_keyword' => 'nullable|string',
            'payroll_cutoff_day' => 'required|integer|min:1|max:31',
            'fitur_pesan_whatsapp' => 'boolean',
            'pesan_whatsapp' => 'nullable|string',
            'google_maps_url' => 'nullable|url|max:1000',
            'is_input_nilai_open' => 'boolean',
            'is_ppdb_open' => 'boolean',
            'logo_upload' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'favicon_upload' => 'nullable|file|mimes:png,jpg,jpeg,svg,ico|max:1024',
        ]);

        $setting = $settingService->get();
        $logoPath = $setting->logo;
        $faviconPath = $setting->favicon;

        if ($this->logo_upload) {
            if ($logoPath) {
                $imageKitService->delete($logoPath);
            }
            $uploadResult = $imageKitService->upload($this->logo_upload, null, '/siska/branding', ['logo', 'branding']);
            $logoPath = $uploadResult->url;
            $this->logo_upload = null;
        }

        if ($this->favicon_upload) {
            if ($faviconPath) {
                $imageKitService->delete($faviconPath);
            }
            $uploadResult = $imageKitService->upload($this->favicon_upload, null, '/siska/branding', ['favicon', 'branding']);
            $faviconPath = $uploadResult->url;
            $this->favicon_upload = null;
        }

        $settingService->update([
            'lembaga' => $validated['lembaga'],
            'nsm' => $validated['nsm'],
            'alamat' => $validated['alamat'],
            'email' => $validated['email_lembaga'],
            'telepon' => $validated['telepon'],
            'meta_deskripsi' => $validated['meta_deskripsi'],
            'meta_keyword' => $validated['meta_keyword'],
            'payroll_cutoff_day' => $validated['payroll_cutoff_day'],
            'fitur_pesan_whatsapp' => $validated['fitur_pesan_whatsapp'],
            'pesan_whatsapp' => $validated['pesan_whatsapp'],
            'google_maps_url' => $validated['google_maps_url'],
            'is_input_nilai_open' => $validated['is_input_nilai_open'],
            'is_ppdb_open' => $validated['is_ppdb_open'],
            'logo' => $logoPath,
            'favicon' => $faviconPath,
        ]);

        Flux::toast(variant: 'success', text: __('Pengaturan lembaga & aplikasi berhasil disimpan.'));
    }

    public function savePageContent(SettingService $settingService): void
    {
        if (Auth::user()?->role !== UserRole::Admin) {
            abort(403);
        }

        $setting = $settingService->get();
        $allCustom = $setting->landing_custom_content ?? [];
        if (! is_array($allCustom)) {
            $allCustom = [];
        }
        if (! isset($allCustom[$this->landing_theme]) || ! is_array($allCustom[$this->landing_theme])) {
            $allCustom[$this->landing_theme] = [];
        }

        $allCustom[$this->landing_theme] = array_merge($allCustom[$this->landing_theme], $this->content);

        $settingService->update([
            'landing_custom_content' => $allCustom,
        ]);

        Flux::toast(variant: 'success', text: __('Konten halaman website berhasil disimpan.'));
    }

    public function removeLogo(SettingService $settingService, ImageKitService $imageKitService): void
    {
        if (Auth::user()?->role !== UserRole::Admin) {
            abort(403);
        }

        $setting = $settingService->get();
        if ($setting->logo) {
            $imageKitService->delete($setting->logo);
        }

        $settingService->update(['logo' => null]);
        $this->logo_upload = null;

        Flux::toast(variant: 'success', text: __('Logo lembaga berhasil dihapus.'));
    }

    public function removeFavicon(SettingService $settingService, ImageKitService $imageKitService): void
    {
        if (Auth::user()?->role !== UserRole::Admin) {
            abort(403);
        }

        $setting = $settingService->get();
        if ($setting->favicon) {
            $imageKitService->delete($setting->favicon);
        }

        $settingService->update(['favicon' => null]);
        $this->favicon_upload = null;

        Flux::toast(variant: 'success', text: __('Favicon lembaga berhasil dihapus.'));
    }

    public function updatedLandingTheme(string $value, SettingService $settingService): void
    {
        $this->saveLandingTheme($settingService);
    }

    public function saveLandingTheme(SettingService $settingService): void
    {
        if (Auth::user()?->role !== UserRole::Admin) {
            abort(403);
        }

        $validated = $this->validate([
            'landing_theme' => 'required|string|in:default,pixigon',
        ]);

        $settingService->update($validated);

        Flux::toast(variant: 'success', text: __('Tema website landing page berhasil diperbarui.'));
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        Flux::toast(variant: 'success', text: __('Profil berhasil diperbarui.'));
    }

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        Flux::toast(variant: 'success', text: __('Kata sandi berhasil diperbarui.'));
    }

    public function render(): View
    {
        $setting = Setting::query()->first();

        return view('livewire.settings.index', [
            'setting' => $setting,
            'isAdmin' => Auth::user()?->role === UserRole::Admin,
        ]);
    }
}
