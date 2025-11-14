<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some sample ingredients
        $potato = Ingredient::firstOrCreate(['name' => 'potatoes']);
        $salt = Ingredient::firstOrCreate(['name' => 'salt']);
        $pepper = Ingredient::firstOrCreate(['name' => 'black pepper']);
        $butter = Ingredient::firstOrCreate(['name' => 'butter']);
        $salmon = Ingredient::firstOrCreate(['name' => 'salmon fillet']);
        $honey = Ingredient::firstOrCreate(['name' => 'honey']);
        $scallops = Ingredient::firstOrCreate(['name' => 'large scallops']);
        $oliveOil = Ingredient::firstOrCreate(['name' => 'olive oil']);
        $garlic = Ingredient::firstOrCreate(['name' => 'garlic']);
        $lemon = Ingredient::firstOrCreate(['name' => 'lemon']);
    }
}
