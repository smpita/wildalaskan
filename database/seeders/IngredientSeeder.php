<?php

namespace Database\Seeders;

use App\Models\Ingredients;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some sample ingredients
        $potato = Ingredients::firstOrCreate(['name' => 'potatoes']);
        $salt = Ingredients::firstOrCreate(['name' => 'salt']);
        $pepper = Ingredients::firstOrCreate(['name' => 'black pepper']);
        $butter = Ingredients::firstOrCreate(['name' => 'butter']);
        $salmon = Ingredients::firstOrCreate(['name' => 'salmon fillet']);
        $honey = Ingredients::firstOrCreate(['name' => 'honey']);
        $scallops = Ingredients::firstOrCreate(['name' => 'large scallops']);
        $oliveOil = Ingredients::firstOrCreate(['name' => 'olive oil']);
        $garlic = Ingredients::firstOrCreate(['name' => 'garlic']);
        $lemon = Ingredients::firstOrCreate(['name' => 'lemon']);
    }
}
