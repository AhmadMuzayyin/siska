<?php

namespace App\Livewire\Admin;

use App\Enums\UserRole;
use App\Models\User as UserModel;
use App\Policies\UserPolicy;
use App\Traits\WithPerPage;
use Flux\Flux;
use Illuminate\Contracts\View\View;
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

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $role = 'guru';

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

        $this->reset(['editingId', 'name', 'email', 'password']);
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
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

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
                ...(filled($data['password']) ? ['password' => Hash::make($data['password'])] : []),
            ]);
        } else {
            $this->authorize('create', UserModel::class);

            UserModel::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => $data['role'],
            ]);
        }

        $this->modal('user-form')->close();
        $this->reset(['editingId', 'name', 'email', 'password']);
        $this->role = 'guru';

        Flux::toast(variant: 'success', text: __('Data pengguna berhasil disimpan.'));
    }

    public function delete(int $id): void
    {
        $user = UserModel::query()->findOrFail($id);
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

        Flux::toast(variant: 'success', text: __('Data pengguna berhasil dihapus.'));
    }

    /**
     * @return LengthAwarePaginator<int, UserModel>
     */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        return UserModel::query()
            ->when($this->search, fn ($query) => $query->where('name', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate($this->perPage);
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
