<?php

use App\Enums\UserRole;
use App\Models\User;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;

test('redirects to google auth endpoint', function () {
    config()->set('services.google.client_id', 'test-client-id');
    config()->set('services.google.client_secret', 'test-client-secret');

    $response = $this->get(route('auth.google'));

    $response->assertRedirect();
});

test('denies google login if user email does not exist in database', function () {
    $abstractUser = Mockery::mock(Laravel\Socialite\Two\User::class);
    $abstractUser->shouldReceive('getEmail')->andReturn('unregistered@example.com');

    $provider = Mockery::mock(Provider::class);
    $provider->shouldReceive('user')->andReturn($abstractUser);

    Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

    $response = $this->get(route('auth.google.callback'));

    $response->assertRedirect(route('login'))
        ->assertSessionHas('error');

    $this->assertGuest();
});

test('denies google login if user exists but is not a guru role', function () {
    $admin = User::factory()->create([
        'email' => 'admin-user@example.com',
        'role' => UserRole::Admin,
    ]);

    $abstractUser = Mockery::mock(Laravel\Socialite\Two\User::class);
    $abstractUser->shouldReceive('getEmail')->andReturn($admin->email);

    $provider = Mockery::mock(Provider::class);
    $provider->shouldReceive('user')->andReturn($abstractUser);

    Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

    $response = $this->get(route('auth.google.callback'));

    $response->assertRedirect(route('login'))
        ->assertSessionHas('error');

    $this->assertGuest();
});

test('allows google login for user with guru role', function () {
    $guru = User::factory()->create([
        'email' => 'ustadz-guru@example.com',
        'role' => UserRole::Guru,
    ]);

    $abstractUser = Mockery::mock(Laravel\Socialite\Two\User::class);
    $abstractUser->shouldReceive('getEmail')->andReturn($guru->email);

    $provider = Mockery::mock(Provider::class);
    $provider->shouldReceive('user')->andReturn($abstractUser);

    Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

    $response = $this->get(route('auth.google.callback'));

    $response->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($guru);
});
