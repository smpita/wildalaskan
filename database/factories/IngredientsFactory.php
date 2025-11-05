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
        $units = ['cup', 'tbsp', 'tsp', 'oz', 'lb', 'cloves', 'large', 'medium', 'small'];
        $items = ['potatoes', 'salt', 'pepper', 'butter', 'onion', 'garlic', 'olive oil', 'flour', 'sugar', 'eggs'];

        return [
            'name' => fake()->unique()->numberBetween(1, 9999) . ' ' .
                     fake()->randomElement($units) . ' ' .
                     fake()->randomElement($items),
        ];
    }
}

