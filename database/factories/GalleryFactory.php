<?php

namespace Database\Factories;

use App\Enums\GalleryType;
use App\Models\Gallery;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Gallery>
 */
class GalleryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(GalleryType::cases()),
            'title' => fake()->sentence(4),
            'image' => fake()->imageUrl(),
            'description' => fake()->optional()->paragraph(),
        ];
    }
}
