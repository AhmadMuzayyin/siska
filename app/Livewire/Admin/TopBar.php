<?php

namespace App\Livewire\Admin;

use App\Enums\SantriStatus;
use App\Models\Contact;
use App\Models\Santri;
use App\Models\Setting;
use App\Services\LembagaService;
use App\Services\SemesterService;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class TopBar extends Component
{
    public string $activeFilter = 'all';

    #[On('semester-changed')]
    #[On('lembaga-changed')]
    public function refreshTopBar(): void
    {
        // Re-renders top-bar component state automatically when semester or lembaga changes
    }

    public function markAsRead(string $type, int $id): void
    {
        if ($type === 'santri') {
            $santri = Santri::query()->find($id);
            if ($santri) {
                $santri->update(['notification_read_at' => now()]);
            }
        } elseif ($type === 'contact' || $type === 'kontak') {
            $contact = Contact::query()->find($id);
            if ($contact) {
                $contact->update(['is_dibaca' => true]);
            }
        }
    }

    public function markAllAsRead(): void
    {
        Contact::query()->where('is_dibaca', false)->update(['is_dibaca' => true]);

        Santri::query()
            ->where('status', SantriStatus::PendingApproval)
            ->whereNull('notification_read_at')
            ->update(['notification_read_at' => now()]);

        Flux::toast(variant: 'success', text: __('Seluruh notifikasi telah ditandai dibaca.'));
    }

    public function render(): View
    {
        $setting = Setting::query()->first();

        /** @var Collection<int, Santri> $pendingSantris */
        $pendingSantris = Santri::query()
            ->with(['lembaga', 'kelas'])
            ->where('status', SantriStatus::PendingApproval)
            ->whereNull('notification_read_at')
            ->latest()
            ->take(10)
            ->get();

        /** @var Collection<int, Contact> $recentContacts */
        $recentContacts = Contact::query()
            ->where('is_dibaca', false)
            ->latest()
            ->take(10)
            ->get();

        $unreadCount = $pendingSantris->count() + $recentContacts->count();
        $activeSemester = app(SemesterService::class)->current();
        $activeLembaga = app(LembagaService::class)->current();
        $activeLembagaName = $activeLembaga?->nama ?? __('Semua Lembaga');

        return view('livewire.admin.top-bar', [
            'pendingSantris' => $pendingSantris,
            'recentContacts' => $recentContacts,
            'unreadCount' => $unreadCount,
            'isMultiLembaga' => (bool) ($setting?->is_multi_lembaga),
            'activeSemester' => $activeSemester,
            'activeLembagaName' => $activeLembagaName,
        ]);
    }
}
