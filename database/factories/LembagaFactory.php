<?php

namespace Database\Factories;

use App\Models\Lembaga;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Lembaga>
 */
class LembagaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nama = fake()->company();

        return [
            'kode' => Str::slug($nama).'-'.fake()->unique()->numberBetween(100, 999),
            'nama' => $nama,
            'jenjang' => fake()->randomElement(['PAUD', 'TPQ', 'MDTA', 'MI', 'MTS', 'MA']),
            'nsm' => fake()->numerify('121235######'),
            'kepala_lembaga' => fake()->name(),
            'alamat' => fake()->address(),
            'telepon' => fake()->numerify('08##########'),
            'is_active' => true,
            'urutan' => fake()->numberBetween(1, 10),
        ];
    }
}
