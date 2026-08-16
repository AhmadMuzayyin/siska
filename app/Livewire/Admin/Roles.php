<?php

namespace App\Livewire\Admin;

use App\Models\User as UserModel;
use App\Traits\WithPerPage;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Title('Manajemen Peran & Izin')]
class Roles extends Component
{
    use WithPagination;
    use WithPerPage;

    public string $search = '';

    public ?int $editingId = null;

    public string $name = '';

    /**
     * @var array<int, string>
     */
    public array $selectedPermissions = [];

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

        $this->reset(['editingId', 'name', 'selectedPermissions']);

        $this->modal('role-form')->show();
    }

    public function edit(int $id): void
    {
        $this->authorize('update', auth()->user());

        $role = Role::query()->with('permissions')->findOrFail($id);

        $this->editingId = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();

        $this->modal('role-form')->show();
    }

    public function save(): void
    {
        $this->authorize('update', auth()->user());

        $this->validate([
            'name' => 'required|string|max:50|unique:roles,name,'.$this->editingId,
            'selectedPermissions' => 'array',
        ], [
            'name.required' => 'Nama peran wajib diisi.',
            'name.unique' => 'Nama peran sudah digunakan.',
        ]);

        $roleSlug = Str::slug(Str::lower($this->name), '_');

        if ($this->editingId) {
            $role = Role::query()->findOrFail($this->editingId);
            $role->update(['name' => $roleSlug]);
        } else {
            $role = Role::create([
                'name' => $roleSlug,
                'guard_name' => 'web',
            ]);
        }

        $role->syncPermissions($this->selectedPermissions);

        $this->modal('role-form')->close();
        $this->reset(['editingId', 'name', 'selectedPermissions']);

        Flux::toast(variant: 'success', text: __('Peran dan izin akses berhasil disimpan.'));
    }

    public function delete(int $id): void
    {
        $this->authorize('delete', auth()->user());

        $role = Role::query()->findOrFail($id);

        if ($role->name === 'admin') {
            Flux::toast(variant: 'danger', text: __('Peran Admin Utama tidak boleh dihapus.'));

            return;
        }

        $role->delete();

        Flux::toast(variant: 'success', text: __('Peran berhasil dihapus.'));
    }

    public function selectAllPermissions(): void
    {
        $this->selectedPermissions = Permission::query()->pluck('name')->toArray();
    }

    public function deselectAllPermissions(): void
    {
        $this->selectedPermissions = [];
    }

    /**
     * @return Collection<int, Role>
     */
    #[Computed]
    public function rolesList(): Collection
    {
        return Role::query()
            ->with(['permissions'])
            ->withCount('users')
            ->when($this->search !== '', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->get();
    }

    /**
     * @return Collection<int, Permission>
     */
    #[Computed]
    public function allPermissions(): Collection
    {
        return Permission::query()->get();
    }

    public function render(): View
    {
        return view('livewire.admin.roles');
    }
}
