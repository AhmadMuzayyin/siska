<?php

namespace App\Livewire\Konten;

use App\Models\Contact as ContactModel;
use App\Traits\WithPerPage;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Pesan Masuk')]
class Contacts extends Component
{
    use WithPagination;
    use WithPerPage;

    public string $search = '';

    public ?int $deletingId = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->authorize('viewAny', ContactModel::class);
        ContactModel::query()->where('is_dibaca', false)->update(['is_dibaca' => true]);
    }

    public function markAsRead(int $id): void
    {
        $contact = ContactModel::query()->find($id);
        if ($contact) {
            $contact->update(['is_dibaca' => true]);
        }
    }

    public function delete(?int $id = null): void
    {
        $targetId = $id ?? $this->deletingId;
        if (! $targetId) {
            return;
        }

        $contact = ContactModel::query()->findOrFail($targetId);
        $this->authorize('delete', $contact);

        $contact->delete();
        $this->deletingId = null;

        Flux::toast(variant: 'success', text: __('Pesan berhasil dihapus.'));
    }

    /**
     * @return LengthAwarePaginator<int, ContactModel>
     */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        return ContactModel::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('subject', 'like', '%'.$this->search.'%');
                });
            })
            ->orderByDesc('id')
            ->paginate($this->perPage);
    }

    public function render(): View
    {
        return view('livewire.konten.contacts');
    }
}
