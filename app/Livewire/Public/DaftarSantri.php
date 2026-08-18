<?php

namespace App\Livewire\Public;

use App\Enums\Gender;
use App\Enums\SantriStatus;
use App\Models\Kelas;
use App\Models\Lembaga;
use App\Models\Santri;
use App\Models\Setting;
use App\Rules\IndonesianPhoneNumber;
use App\Services\TelegramService;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Pendaftaran Santri Baru')]
#[Layout('layouts.public.shell')]
class DaftarSantri extends Component
{
    public string $nama_lengkap = '';

    public string $nama_panggilan = '';

    public string $jenis_kelamin = '';

    public string $tempat_lahir = '';

    public string $tanggal_lahir = '';

    public int $anak_ke = 1;

    public ?int $lembaga_id = null;

    public ?int $kelas_id = null;

    public string $noinduk = '';

    public string $telepon_wali = '';

    public string $nama_ayah = '';

    public string $pendidikan_ayah = '';

    public string $pekerjaan_ayah = '';

    public string $nama_ibu = '';

    public string $pendidikan_ibu = '';

    public string $pekerjaan_ibu = '';

    public string $alamat = '';

    public bool $submitted = false;

    public function updatedLembagaId(): void
    {
        // Reset kelas_id when changing lembaga if it doesn't belong to the selected lembaga
        if ($this->kelas_id && $this->lembaga_id) {
            $currentKelas = Kelas::query()->find($this->kelas_id);
            if ($currentKelas && $currentKelas->lembaga_id !== (int) $this->lembaga_id) {
                $this->kelas_id = null;
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nama_panggilan' => ['nullable', 'string', 'max:255'],
            'jenis_kelamin' => ['required', Rule::enum(Gender::class)],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date', 'before:today'],
            'anak_ke' => ['nullable', 'integer', 'min:1', 'max:255'],
            'lembaga_id' => ['nullable', 'integer', 'exists:lembagas,id'],
            'kelas_id' => ['nullable', 'integer', 'exists:kelas,id'],
            'noinduk' => ['nullable', 'string', 'max:50', 'unique:santris,noinduk'],
            'telepon_wali' => ['required', 'string', new IndonesianPhoneNumber],
            'nama_ayah' => ['nullable', 'string', 'max:255'],
            'pendidikan_ayah' => ['nullable', 'string', 'max:255'],
            'pekerjaan_ayah' => ['nullable', 'string', 'max:255'],
            'nama_ibu' => ['nullable', 'string', 'max:255'],
            'pendidikan_ibu' => ['nullable', 'string', 'max:255'],
            'pekerjaan_ibu' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap calon santri wajib diisi.',
            'jenis_kelamin.required' => 'Silakan pilih jenis kelamin santri.',
            'telepon_wali.required' => 'Nomor WhatsApp aktif wali santri wajib diisi.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'noinduk.unique' => 'Nomor induk / NISN sudah terdaftar di sistem.',
        ];
    }

    public function register(): void
    {
        $validated = $this->validate();

        $validated['status'] = SantriStatus::PendingApproval;

        if (empty($validated['lembaga_id']) && ! empty($validated['kelas_id'])) {
            $validated['lembaga_id'] = Kelas::query()->find($validated['kelas_id'])?->lembaga_id;
        }

        // Fill fallback values for non-nullable database fields if any
        $validated['nama_panggilan'] = filled($validated['nama_panggilan']) ? $validated['nama_panggilan'] : $validated['nama_lengkap'];
        $validated['tempat_lahir'] = filled($validated['tempat_lahir']) ? $validated['tempat_lahir'] : '-';
        $validated['tanggal_lahir'] = filled($validated['tanggal_lahir']) ? $validated['tanggal_lahir'] : now()->subYears(7)->format('Y-m-d');
        $validated['anak_ke'] = $validated['anak_ke'] ?: 1;
        $validated['alamat'] = filled($validated['alamat']) ? $validated['alamat'] : '-';
        $validated['nama_ayah'] = filled($validated['nama_ayah']) ? $validated['nama_ayah'] : '-';
        $validated['pendidikan_ayah'] = filled($validated['pendidikan_ayah']) ? $validated['pendidikan_ayah'] : '-';
        $validated['pekerjaan_ayah'] = filled($validated['pekerjaan_ayah']) ? $validated['pekerjaan_ayah'] : '-';
        $validated['nama_ibu'] = filled($validated['nama_ibu']) ? $validated['nama_ibu'] : '-';
        $validated['pendidikan_ibu'] = filled($validated['pendidikan_ibu']) ? $validated['pendidikan_ibu'] : '-';
        $validated['pekerjaan_ibu'] = filled($validated['pekerjaan_ibu']) ? $validated['pekerjaan_ibu'] : '-';

        $santri = Santri::query()->create($validated);

        // Kirim notifikasi Telegram ke Admin
        app(TelegramService::class)->sendNewSantriNotification($santri);

        $this->submitted = true;
        session()->flash('status', 'Pendaftaran berhasil dikirim, menunggu konfirmasi admin.');
    }

    public function render(): View
    {
        $setting = Setting::query()->first();

        $kelasListQuery = Kelas::query()->with('lembaga')->orderBy('nama');
        if ($this->lembaga_id) {
            $kelasListQuery->where('lembaga_id', $this->lembaga_id);
        }

        return view('livewire.public.daftar-santri', [
            'setting' => $setting,
            'theme' => $setting?->landing_theme ?? 'default',
            'lembagas' => Lembaga::query()->active()->ordered()->with('kelas')->get(),
            'kelasList' => $kelasListQuery->get(),
            'genders' => Gender::cases(),
        ]);
    }
}
