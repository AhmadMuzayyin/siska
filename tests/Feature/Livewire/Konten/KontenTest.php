<?php

use App\Enums\UserRole;
use App\Livewire\Konten\Contacts;
use App\Livewire\Konten\Galleries;
use App\Livewire\Konten\Subscriptions;
use App\Livewire\Public\NewsletterForm;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
});

test('renders the galleries page and creates a photo', function () {
    $this->actingAs($this->admin)
        ->get(route('konten.galeri'))
        ->assertOk()
        ->assertSeeLivewire(Galleries::class);

    Livewire::actingAs($this->admin)
        ->test(Galleries::class)
        ->set('type', 'kegiatan')
        ->set('title', 'Kegiatan Ramadhan')
        ->set('image', 'https://example.com/foto.jpg')
        ->call('save')
        ->assertHasNoErrors();

    expect(Gallery::query()->where('title', 'Kegiatan Ramadhan')->exists())->toBeTrue();
});

test('renders the contacts page and deletes a message', function () {
    $contact = Contact::factory()->create();

    $this->actingAs($this->admin)
        ->get(route('konten.pesan'))
        ->assertOk()
        ->assertSeeLivewire(Contacts::class);

    Livewire::actingAs($this->admin)
        ->test(Contacts::class)
        ->call('delete', $contact->id);

    expect(Contact::query()->whereKey($contact->id)->exists())->toBeFalse();
});

test('renders the subscriptions page and deletes a subscription', function () {
    $subscription = Subscription::factory()->create();

    $this->actingAs($this->admin)
        ->get(route('konten.langganan'))
        ->assertOk()
        ->assertSeeLivewire(Subscriptions::class);

    Livewire::actingAs($this->admin)
        ->test(Subscriptions::class)
        ->call('delete', $subscription->id);

    expect(Subscription::query()->whereKey($subscription->id)->exists())->toBeFalse();
});

test('public user can subscribe to newsletter', function () {
    Livewire::test(NewsletterForm::class)
        ->set('email', 'walisantri@example.com')
        ->call('subscribe')
        ->assertHasNoErrors()
        ->assertSet('submitted', true);

    expect(Subscription::query()->where('email', 'walisantri@example.com')->exists())->toBeTrue();
});

test('admin can send broadcast email to all newsletter subscribers', function () {
    Mail::fake();

    Subscription::factory()->create(['email' => 'subscriber1@example.com']);
    Subscription::factory()->create(['email' => 'subscriber2@example.com']);

    Livewire::actingAs($this->admin)
        ->test(Subscriptions::class)
        ->set('subjek', 'Pengumuman Libur Hari Raya')
        ->set('pesan', 'Informasi mengenai jadwal libur resmi santri.')
        ->call('sendBroadcast')
        ->assertHasNoErrors();
});
