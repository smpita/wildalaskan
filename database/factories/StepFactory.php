<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\Step;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Step>
 */
class StepFactory extends Factory
{
    /**
     * The step numbers for each recipe.
     *
     * @var array<int, int>
     */
    protected static array $stepNumbers = [];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'recipe_id' => Recipe::factory(),
            'description' => fake()->sentence(),
            'step_number' => fake()->numberBetween(1, 1000),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Step $step) {
            $key = $step->recipe_id;
            static::$stepNumbers[$key] = (static::$stepNumbers[$key] ?? 0) + 1;

            $step->step_number = static::$stepNumbers[$key];
            $step->save();
        });
    }
}
