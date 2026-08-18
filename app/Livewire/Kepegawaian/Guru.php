<?php

namespace App\Livewire\Kepegawaian;

use App\Actions\DeleteGuruAction;
use App\Actions\SaveGuruAction;
use App\Enums\Gender;
use App\Enums\GuruStatus;
use App\Exceptions\GuruMasihDipakaiException;
use App\Models\Guru as GuruModel;
use App\Rules\IndonesianPhoneNumber;
use App\Traits\WithPerPage;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Data Guru')]
class Guru extends Component
{
    use WithPagination;
    use WithPerPage;

    public string $search = '';

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $alamat = '';

    public string $whatsapp = '';

    public string $gender = '';

    public string $status = 'aktif';

    public string $rfid_uid = '';

    public function mount(): void
    {
        $this->authorize('viewAny', GuruModel::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->authorize('create', GuruModel::class);

        $this->reset(['editingId', 'name', 'email', 'password', 'alamat', 'whatsapp', 'gender', 'rfid_uid']);
        $this->status = 'aktif';

        $this->modal('guru-form')->show();
    }

    public function edit(int $id): void
    {
        $guru = GuruModel::query()->with('user')->findOrFail($id);
        $this->authorize('update', $guru);

        $this->editingId = $guru->id;
        $this->name = $guru->user->name;
        $this->email = $guru->user->email;
        $this->password = '';
        $this->alamat = $guru->alamat;
        $this->whatsapp = $guru->whatsapp;
        $this->gender = $guru->gender->value;
        $this->status = $guru->status->value;
        $this->rfid_uid = (string) $guru->rfid_uid;

        $this->modal('guru-form')->show();
    }

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        $userId = $this->editingId ? GuruModel::query()->find($this->editingId)?->user_id : null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'password' => [$this->editingId ? 'nullable' : 'required', 'string', 'min:8'],
            'alamat' => ['required', 'string'],
            'whatsapp' => ['required', 'string', new IndonesianPhoneNumber],
            'gender' => ['required', Rule::enum(Gender::class)],
            'status' => ['required', Rule::enum(GuruStatus::class)],
            'rfid_uid' => ['nullable', 'string', Rule::unique('gurus', 'rfid_uid')->ignore($this->editingId)],
        ];
    }

    public function save(SaveGuruAction $action): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            $guru = GuruModel::query()->with('user')->findOrFail($this->editingId);
            $this->authorize('update', $guru);

            $action->handle($data, $guru);
        } else {
            $this->authorize('create', GuruModel::class);

            $action->handle($data);
        }

        $this->modal('guru-form')->close();
        $this->reset(['editingId', 'name', 'email', 'password', 'alamat', 'whatsapp', 'gender', 'rfid_uid']);
        $this->status = 'aktif';

        Flux::toast(variant: 'success', text: __('Data guru berhasil disimpan.'));
    }

    public function delete(?int $id = null, ?DeleteGuruAction $action = null): void
    {
        $targetId = $id ?? $this->deletingId;
        if (! $targetId) {
            return;
        }

        $guru = GuruModel::query()->findOrFail($targetId);
        $this->authorize('delete', $guru);

        $action = $action ?? app(DeleteGuruAction::class);

        try {
            $action->handle($guru);
            $this->deletingId = null;
            Flux::toast(variant: 'success', text: __('Data guru berhasil dihapus.'));
        } catch (GuruMasihDipakaiException $exception) {
            Flux::toast(variant: 'danger', text: $exception->getMessage());
        }
    }

    public function toggleStatus(int $id): void
    {
        $guru = GuruModel::query()->with('user')->findOrFail($id);
        $this->authorize('update', $guru);

        $newStatus = $guru->status === GuruStatus::Aktif ? GuruStatus::TidakAktif : GuruStatus::Aktif;
        $updates = ['status' => $newStatus];
        if ($newStatus === GuruStatus::Aktif && is_null($guru->notification_read_at)) {
            $updates['notification_read_at'] = now();
        }
        $guru->update($updates);

        $message = $newStatus === GuruStatus::Aktif
            ? __('Akun guru :name berhasil diaktifkan.', ['name' => $guru->user->name])
            : __('Akun guru :name dinonaktifkan.', ['name' => $guru->user->name]);

        Flux::toast(variant: 'success', text: $message);
    }

    /**
     * @return LengthAwarePaginator<int, GuruModel>
     */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        return GuruModel::query()
            ->with('user')
            ->when($this->search, fn ($query) => $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$this->search}%")))
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);
    }

    /**
     * @return array<int, Gender>
     */
    #[Computed]
    public function genders(): array
    {
        return Gender::cases();
    }

    /**
     * @return array<int, GuruStatus>
     */
    #[Computed]
    public function statuses(): array
    {
        return GuruStatus::cases();
    }

    public function render(): View
    {
        return view('livewire.kepegawaian.guru');
    }
}
