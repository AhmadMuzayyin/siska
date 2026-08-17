<?php

namespace App\Livewire\Konten;

use App\Models\Subscription as SubscriptionModel;
use App\Traits\WithPerPage;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Langganan Newsletter')]
class Subscriptions extends Component
{
    use WithPagination;
    use WithPerPage;

    public string $search = '';

    public ?int $deletingId = null;

    #[Validate('required|string|max:255')]
    public string $subjek = '';

    #[Validate('required|string|min:10')]
    public string $pesan = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->authorize('viewAny', SubscriptionModel::class);
    }

    public function openBroadcastModal(): void
    {
        $this->authorize('create', SubscriptionModel::class);

        $this->reset(['subjek', 'pesan']);
        $this->modal('broadcast-modal')->show();
    }

    public function sendBroadcast(): void
    {
        $this->authorize('create', SubscriptionModel::class);

        $this->validate([
            'subjek' => 'required|string|max:255',
            'pesan' => 'required|string|min:10',
        ]);

        $subscribers = SubscriptionModel::query()->pluck('email')->all();

        if (empty($subscribers)) {
            Flux::toast(variant: 'warning', text: __('Tidak ada email langganan newsletter yang terdaftar.'));

            return;
        }

        // Send newsletter emails to all active subscribers
        foreach ($subscribers as $email) {
            try {
                Mail::raw($this->pesan, function ($message) use ($email) {
                    $message->to($email)
                        ->subject($this->subjek);
                });
            } catch (\Throwable $e) {
                // Ignore individual transport failures gracefully
            }
        }

        $this->modal('broadcast-modal')->close();
        $this->reset(['subjek', 'pesan']);

        Flux::toast(
            variant: 'success',
            text: __('Broadcast newsletter berhasil dikirimkan ke :count penerima.', ['count' => count($subscribers)])
        );
    }

    public function delete(?int $id = null): void
    {
        $targetId = $id ?? $this->deletingId;
        if (! $targetId) {
            return;
        }

        $subscription = SubscriptionModel::query()->findOrFail($targetId);
        $this->authorize('delete', $subscription);

        $subscription->delete();
        $this->deletingId = null;

        Flux::toast(variant: 'success', text: __('Langganan berhasil dihapus.'));
    }

    /**
     * @return LengthAwarePaginator<int, SubscriptionModel>
     */
    #[Computed]
    public function rows(): LengthAwarePaginator
    {
        return SubscriptionModel::query()
            ->when($this->search !== '', fn ($query) => $query->where('email', 'like', '%'.$this->search.'%'))
            ->orderByDesc('id')
            ->paginate($this->perPage);
    }

    #[Computed]
    public function totalSubscribers(): int
    {
        return SubscriptionModel::query()->count();
    }

    public function render(): View
    {
        return view('livewire.konten.subscriptions');
    }
}
