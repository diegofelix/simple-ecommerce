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
            'name' => $this->faker->name,
            'slug' => $this->faker->slug,
            'images' => [$this->faker->imageUrl],
            'description' => $this->faker->text,
            'price' => $this->faker->randomDigit(),
            'stock' => $this->faker->randomDigit(),
            'is_active' => $this->faker->boolean,
            'is_featured' => $this->faker->boolean,
            'on_sale' => $this->faker->boolean,
        ];
    }
}
