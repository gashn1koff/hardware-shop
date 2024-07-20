<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => ucfirst($this->faker->words(2, true)),
            'thumbnail' => $this->faker->file(
                base_path('/tests/Fixtures/images/'),
                storage_path('app/public/images/'),
                false
            ),
            'description' => ucfirst($this->faker->words(5, true)),
            'price' => $this->faker->numberBetween(1000, 2000000),
        ];
    }
}
