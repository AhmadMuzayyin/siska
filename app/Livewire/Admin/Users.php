<?php

namespace App\Livewire\Admin;

use App\Enums\UserRole;
use App\Models\Lembaga;
use App\Models\User as UserModel;
use App\Policies\UserPolicy;
use App\Traits\WithPerPage;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Manajemen Pengguna')]
class Users extends Component
{
    use WithPagination;
    use WithPerPage;

    public string $search = '';

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $role = 'guru';

    public ?int $lembaga_id = null;

    public function mount(): void
    {
        $this->authorize('viewAny', UserModel::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->authorize('create', UserModel::class);

        $this->reset(['editingId', 'name', 'email', 'password', 'lembaga_id']);
        $this->role = 'guru';

        $this->modal('user-form')->show();
    }

    public function edit(int $id): void
    {
        $user = UserModel::query()->findOrFail($id);
        $this->authorize('update', $user);

        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->role = $user->role->value;
        $this->lembaga_id = $user->lembaga_id;

        $this->modal('user-form')->show();
    }

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->editingId)],
            'password' => [$this->editingId ? 'nullable' : 'required', 'string', 'min:8'],
            'role' => ['required', Rule::enum(UserRole::class)],
            'lembaga_id' => [
                Rule::requiredIf(fn () => $this->role === UserRole::Operator->value),
                'nullable',
                'integer',
                'exists:lembagas,id',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'lembaga_id.required' => 'Pengguna dengan peran Operator wajib terikat pada Unit Lembaga tertentu.',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        $lembagaId = $data['role'] === UserRole::Operator->value ? $data['lembaga_id'] : $data['lembaga_id'];

        if ($this->editingId) {
            $user = UserModel::query()->findOrFail($this->editingId);
            $this->authorize('update', $user);

            if (app(UserPolicy::class)->isProtectedAdmin($user) && $data['role'] !== UserRole::Admin->value) {
                Flux::toast(variant: 'danger', text: __('Peran akun admin utama tidak bisa diubah.'));

                return;
            }

            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'lembaga_id' => $lembagaId,
                ...(filled($data['password']) ? ['password' => Hash::make($data['password'])] : []),
            ]);
        } else {
            $this->authorize('create', UserModel::class);

            UserModel::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => $data['role'],
                'lembaga_id' => $lembagaId,
            ]);
        }

        $this->modal('user-form')->close();
        $this->reset(['editingId', 'name', 'email', 'password', 'lembaga_id']);
        $this->role = 'guru';

        Flux::toast(variant: 'success', text: __('Data pengguna berhasil disimpan.'));
    }

    public function delete(?int $id = null): void
    {
        $targetId = $id ?? $this->deletingId;
        if (! $targetId) {
            return;
        }

        $user = UserModel::query()->findOrFail($targetId);
        $this->authorize('delete', $user);

        if (app(UserPolicy::class)->isProtectedAdmin($user)) {
            Flux::toast(variant: 'danger', text: __('Akun admin utama tidak bisa dihapus.'));

            return;
        }

        if ($user->guru()->exists()) {
            Flux::toast(variant: 'danger', text: __('Akun ini terhubung ke profil guru. Hapus melalui menu Data Guru.'));

            return;
        }

        $user->delete();
        $this->deletingId = null;

        Flux::toast(variant: 'success', text: __('Pengguna berhasil dihapus.'));
    }

    /**
     * @return LengthAwarePaginator<int, UserModel>
     */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        return UserModel::query()
            ->with('lembaga')
            ->when($this->search, fn ($query) => $query->where('name', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate($this->perPage);
    }

    /**
     * @return Collection<int, Lembaga>
     */
    #[Computed]
    public function lembagasOptions(): Collection
    {
        $lembagas = Lembaga::query()->active()->ordered()->get();

        if ($lembagas->count() === 1 && ! $this->lembaga_id) {
            $this->lembaga_id = $lembagas->first()->id;
        }

        return $lembagas;
    }

    /**
     * @return array<int, UserRole>
     */
    #[Computed]
    public function roles(): array
    {
        return UserRole::cases();
    }

    public function render(): View
    {
        return view('livewire.admin.users');
    }
}
