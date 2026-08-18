<?php

use App\Enums\AccountType;
use App\Enums\GuruStatus;
use App\Enums\UserRole;
use App\Models\Guru;
use App\Models\User;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;

test('redirects to google auth endpoint', function () {
    config()->set('services.google.client_id', 'test-client-id');
    config()->set('services.google.client_secret', 'test-client-secret');

    $response = $this->get(route('auth.google'));

    $response->assertRedirect();
});

test('auto registers new guru account with inactive status when email does not exist in database', function () {
    $email = 'new-teacher@gmail.com';

    $abstractUser = Mockery::mock(Laravel\Socialite\Two\User::class);
    $abstractUser->shouldReceive('getEmail')->andReturn($email);
    $abstractUser->shouldReceive('getName')->andReturn('Ustadz Ahmad Google');

    $provider = Mockery::mock(Provider::class);
    $provider->shouldReceive('user')->andReturn($abstractUser);

    Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

    $response = $this->get(route('auth.google.callback'));

    $response->assertRedirect(route('login'))
        ->assertSessionHas('warning');

    $this->assertGuest();

    // Verify user created with Guru role & Google account type
    $user = User::query()->where('email', $email)->first();
    expect($user)->not->toBeNull();
    expect($user->name)->toBe('Ustadz Ahmad Google');
    expect($user->role)->toBe(UserRole::Guru);
    expect($user->account_type)->toBe(AccountType::Google);

    // Verify linked Guru created with TidakAktif status (requires admin confirmation)
    $guru = Guru::query()->where('user_id', $user->id)->first();
    expect($guru)->not->toBeNull();
    expect($guru->status)->toBe(GuruStatus::TidakAktif);
});

test('denies google login if guru account is not yet activated by admin', function () {
    $user = User::factory()->create([
        'email' => 'pending-guru@gmail.com',
        'role' => UserRole::Guru,
    ]);

    Guru::factory()->create([
        'user_id' => $user->id,
        'status' => GuruStatus::TidakAktif,
    ]);

    $abstractUser = Mockery::mock(Laravel\Socialite\Two\User::class);
    $abstractUser->shouldReceive('getEmail')->andReturn($user->email);
    $abstractUser->shouldReceive('getName')->andReturn($user->name);

    $provider = Mockery::mock(Provider::class);
    $provider->shouldReceive('user')->andReturn($abstractUser);

    Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

    $response = $this->get(route('auth.google.callback'));

    $response->assertRedirect(route('login'))
        ->assertSessionHas('warning');

    $this->assertGuest();
});

test('allows google login for activated guru account', function () {
    $user = User::factory()->create([
        'email' => 'active-guru@gmail.com',
        'role' => UserRole::Guru,
    ]);

    Guru::factory()->create([
        'user_id' => $user->id,
        'status' => GuruStatus::Aktif,
    ]);

    $abstractUser = Mockery::mock(Laravel\Socialite\Two\User::class);
    $abstractUser->shouldReceive('getEmail')->andReturn($user->email);
    $abstractUser->shouldReceive('getName')->andReturn($user->name);

    $provider = Mockery::mock(Provider::class);
    $provider->shouldReceive('user')->andReturn($abstractUser);

    Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

    $response = $this->get(route('auth.google.callback'));

    $response->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('denies google login if user exists but is not a guru role', function () {
    $admin = User::factory()->create([
        'email' => 'admin-user@example.com',
        'role' => UserRole::Admin,
    ]);

    $abstractUser = Mockery::mock(Laravel\Socialite\Two\User::class);
    $abstractUser->shouldReceive('getEmail')->andReturn($admin->email);
    $abstractUser->shouldReceive('getName')->andReturn($admin->name);

    $provider = Mockery::mock(Provider::class);
    $provider->shouldReceive('user')->andReturn($abstractUser);

    Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

    $response = $this->get(route('auth.google.callback'));

    $response->assertRedirect(route('login'))
        ->assertSessionHas('error');

    $this->assertGuest();
});
