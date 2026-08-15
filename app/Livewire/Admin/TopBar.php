<?php

namespace App\Livewire\Admin;

use App\Enums\SantriStatus;
use App\Models\Contact;
use App\Models\Santri;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class TopBar extends Component
{
    public string $activeFilter = 'all';

    public function render(): View
    {
        $setting = Setting::query()->first();

        /** @var Collection<int, Santri> $pendingSantris */
        $pendingSantris = Santri::query()
            ->with(['lembaga', 'kelas'])
            ->where('status', SantriStatus::PendingApproval)
            ->latest()
            ->take(10)
            ->get();

        /** @var Collection<int, Contact> $recentContacts */
        $recentContacts = Contact::query()
            ->latest()
            ->take(10)
            ->get();

        $unreadCount = $pendingSantris->count() + $recentContacts->count();

        return view('livewire.admin.top-bar', [
            'pendingSantris' => $pendingSantris,
            'recentContacts' => $recentContacts,
            'unreadCount' => $unreadCount,
            'isMultiLembaga' => (bool) ($setting?->is_multi_lembaga),
        ]);
    }
}
