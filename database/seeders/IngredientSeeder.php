<?php

namespace Database\Seeders;

use App\Models\Ingredients;
use App\Models\Recipe;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some sample ingredients
        $potato = Ingredients::firstOrCreate(['name' => '3 large potatoes']);
        $salt = Ingredients::firstOrCreate(['name' => '1 tsp salt']);
        $pepper = Ingredients::firstOrCreate(['name' => '1/2 tsp black pepper']);
        $butter = Ingredients::firstOrCreate(['name' => '2 tbsp butter']);
        $salmon = Ingredients::firstOrCreate(['name' => '1 lb salmon fillet']);
        $honey = Ingredients::firstOrCreate(['name' => '2 tbsp honey']);
        $scallops = Ingredients::firstOrCreate(['name' => '8 large scallops']);
        $oliveOil = Ingredients::firstOrCreate(['name' => '2 tbsp olive oil']);
        $garlic = Ingredients::firstOrCreate(['name' => '3 cloves garlic']);
        $lemon = Ingredients::firstOrCreate(['name' => '1 lemon']);
    }
}

