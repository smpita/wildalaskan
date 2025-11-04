<?php

namespace Database\Factories;

use App\Models\Ingredients;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingredients>
 */
class IngredientsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantities = ['1', '2', '3', '1/2', '1/4', '1/3'];
        $units = ['cup', 'tbsp', 'tsp', 'oz', 'lb', 'cloves', 'large', 'medium', 'small'];
        $items = ['potatoes', 'salt', 'pepper', 'butter', 'onion', 'garlic', 'olive oil', 'flour', 'sugar', 'eggs'];

        return [
            'name' => fake()->unique()->randomElement($quantities) . ' ' .
                     fake()->randomElement($units) . ' ' .
                     fake()->randomElement($items),
        ];
    }

    /**
     * Indicate that the ingredient should have a specific name.
     */
    public function withName(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name,
        ]);
    }
}

