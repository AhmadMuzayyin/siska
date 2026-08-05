<?php

use App\Models\Contact;

test('a visitor can submit the contact form with valid data', function () {
    $this->post(route('contact.store'), [
        'name' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'subject' => 'Pertanyaan pendaftaran',
        'message' => 'Apakah masih ada kuota pendaftaran santri baru?',
    ])
        ->assertRedirect()
        ->assertSessionHas('status');

    expect(Contact::query()->where('email', 'budi@example.com')->exists())->toBeTrue();
});

test('the contact form rejects invalid data', function () {
    $this->post(route('contact.store'), [
        'name' => '',
        'email' => 'not-an-email',
        'subject' => '',
        'message' => '',
    ])
        ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);

    expect(Contact::query()->count())->toBe(0);
});
