<?php

namespace Database\Factories;

use App\Models\ConnectedAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ConnectedAccount>
 */
class ConnectedAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'provider' => 'google',
            'provider_user_id' => fake()->uuid(),
            'name' => fake()->name(),
            'nickname' => fake()->userName(),
            'email' => fake()->safeEmail(),
            'phone' => null,
            'avatar' => fake()->imageUrl(),
            'token' => fake()->sha256(),
            'refresh_token' => fake()->sha256(),
            'expires_at' => now()->addHour(),
        ];
    }
}
