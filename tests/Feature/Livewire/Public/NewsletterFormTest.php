<?php

use App\Livewire\Public\NewsletterForm;
use App\Models\Subscription;
use Livewire\Livewire;

test('a visitor can subscribe with a new email', function () {
    Livewire::test(NewsletterForm::class)
        ->set('email', 'wali@example.com')
        ->call('subscribe')
        ->assertHasNoErrors()
        ->assertSet('submitted', true)
        ->assertSet('email', '');

    expect(Subscription::query()->where('email', 'wali@example.com')->exists())->toBeTrue();
});

test('subscribing with an already subscribed email fails validation', function () {
    Subscription::factory()->create(['email' => 'wali@example.com']);

    Livewire::test(NewsletterForm::class)
        ->set('email', 'wali@example.com')
        ->call('subscribe')
        ->assertHasErrors(['email']);

    expect(Subscription::query()->where('email', 'wali@example.com')->count())->toBe(1);
});
