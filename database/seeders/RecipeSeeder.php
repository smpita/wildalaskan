<?php

namespace Database\Seeders;

use App\Models\Ingredients;
use App\Models\Recipe;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some sample ingredients
        $potato = Ingredients::where('name', 'potatoes')->firstOrFail();
        $salt = Ingredients::where('name', 'salt')->firstOrFail();
        $pepper = Ingredients::where('name', 'black pepper')->firstOrFail();
        $butter = Ingredients::where('name', 'butter')->firstOrFail();
        $salmon = Ingredients::where('name', 'salmon fillet')->firstOrFail();
        $honey = Ingredients::where('name', 'honey')->firstOrFail();
        $scallops = Ingredients::where('name', 'large scallops')->firstOrFail();
        $oliveOil = Ingredients::where('name', 'olive oil')->firstOrFail();
        $garlic = Ingredients::where('name', 'garlic')->firstOrFail();
        $lemon = Ingredients::where('name', 'lemon')->firstOrFail();

        $amounts = ['1/4', '1/3', '1/2', '2/3', '3/4', '1', '2', '3', '4', '5'];
        $units = ['cup', 'tbsp', 'tsp', 'pinch', 'oz', 'lb', 'g', 'ml', 'l'];

        // Recipe 1: Honey Glazed Salmon - chef@example.com
        $recipe1 = Recipe::create([
            'name' => 'Honey Glazed Salmon',
            'description' => 'A delicious and healthy salmon dish with a sweet honey glaze. Perfect for a weeknight dinner.',
            'author_email' => 'chef@example.com',
        ]);
        $ingredients1 = [
            ['id' => $salmon->id, 'amount' => fake()->randomElement($amounts), 'unit' => fake()->randomElement($units)],
            ['id' => $honey->id, 'amount' => fake()->randomElement($amounts), 'unit' => fake()->randomElement($units)],
            ['id' => $oliveOil->id, 'amount' => fake()->randomElement($amounts), 'unit' => fake()->randomElement($units)],
            ['id' => $garlic->id, 'amount' => fake()->randomElement($amounts), 'unit' => fake()->randomElement($units)],
            ['id' => $lemon->id, 'amount' => fake()->randomElement($amounts), 'unit' => fake()->randomElement($units)],
        ];
        foreach ($ingredients1 as $ingredient) {
            $recipe1->ingredients()->attach($ingredient['id'], [
                'amount' => $ingredient['amount'],
                'unit' => $ingredient['unit'],
            ]);
        }
        $steps1 = [
            ['description' => 'Preheat oven to 400°F', 'step_number' => 1],
            ['description' => 'Place salmon fillet on a baking sheet', 'step_number' => 2],
            ['description' => 'Mix honey, olive oil, and garlic in a small bowl', 'step_number' => 3],
            ['description' => 'Brush the honey mixture over the salmon', 'step_number' => 4],
            ['description' => 'Bake for 12-15 minutes until fish flakes easily', 'step_number' => 5],
            ['description' => 'Serve with lemon wedges', 'step_number' => 6],
        ];
        $recipe1->steps()->createMany($steps1);

        // Recipe 2: Scalloped Potatoes - foo@bar.com
        $recipe2 = Recipe::create([
            'name' => 'Scalloped Potatoes',
            'description' => 'Creamy, cheesy scalloped potatoes that are perfect as a side dish. The scalloped potatoes are layered with a rich sauce.',
            'author_email' => 'foo@bar.com',
        ]);
        $ingredients2 = [
            ['id' => $potato->id, 'amount' => fake()->randomElement($amounts), 'unit' => fake()->randomElement($units)],
            ['id' => $salt->id, 'amount' => fake()->randomElement($amounts), 'unit' => fake()->randomElement($units)],
            ['id' => $pepper->id, 'amount' => fake()->randomElement($amounts), 'unit' => fake()->randomElement($units)],
            ['id' => $butter->id, 'amount' => fake()->randomElement($amounts), 'unit' => fake()->randomElement($units)],
        ];
        foreach ($ingredients2 as $ingredient) {
            $recipe2->ingredients()->attach($ingredient['id'], [
                'amount' => $ingredient['amount'],
                'unit' => $ingredient['unit'],
            ]);
        }
        $steps2 = [
            ['description' => 'Preheat oven to 375°F', 'step_number' => 1],
            ['description' => 'Slice potatoes into thin rounds', 'step_number' => 2],
            ['description' => 'Layer potatoes in a baking dish', 'step_number' => 3],
            ['description' => 'Melt butter and mix with salt and pepper', 'step_number' => 4],
            ['description' => 'Pour butter mixture over potatoes', 'step_number' => 5],
            ['description' => 'Bake for 45 minutes until tender and golden', 'step_number' => 6],
        ];
        $recipe2->steps()->createMany($steps2);

        // Recipe 3: Seared Scallops with Potato Puree - foo@bar.com
        $recipe3 = Recipe::create([
            'name' => 'Seared Scallops with Potato Puree',
            'description' => 'Gourmet seared scallops served over creamy potato puree. A restaurant-quality dish with scallops and potatoes.',
            'author_email' => 'foo@bar.com',
        ]);
        $ingredients3 = [
            ['id' => $scallops->id, 'amount' => fake()->randomElement($amounts), 'unit' => fake()->randomElement($units)],
            ['id' => $potato->id, 'amount' => fake()->randomElement($amounts), 'unit' => fake()->randomElement($units)],
            ['id' => $butter->id, 'amount' => fake()->randomElement($amounts), 'unit' => fake()->randomElement($units)],
            ['id' => $salt->id, 'amount' => fake()->randomElement($amounts), 'unit' => fake()->randomElement($units)],
            ['id' => $pepper->id, 'amount' => fake()->randomElement($amounts), 'unit' => fake()->randomElement($units)],
            ['id' => $oliveOil->id, 'amount' => fake()->randomElement($amounts), 'unit' => fake()->randomElement($units)],
        ];
        foreach ($ingredients3 as $ingredient) {
            $recipe3->ingredients()->attach($ingredient['id'], [
                'amount' => $ingredient['amount'],
                'unit' => $ingredient['unit'],
            ]);
        }
        $steps3 = [
            ['description' => 'Boil potatoes until tender', 'step_number' => 1],
            ['description' => 'Mash potatoes with butter, salt, and pepper', 'step_number' => 2],
            ['description' => 'Heat olive oil in a large skillet', 'step_number' => 3],
            ['description' => 'Season scallops with salt and pepper', 'step_number' => 4],
            ['description' => 'Sear scallops for 2-3 minutes per side until golden', 'step_number' => 5],
            ['description' => 'Serve scallops over potato puree', 'step_number' => 6],
        ];
        $recipe3->steps()->createMany($steps3);

        // Recipe 4: Simple Potato Salad - chef@example.com
        $recipe4 = Recipe::create([
            'name' => 'Simple Potato Salad',
            'description' => 'A classic potato salad recipe that goes great with any barbecue or picnic.',
            'author_email' => 'chef@example.com',
        ]);
        $ingredients4 = [
            ['id' => $potato->id, 'amount' => fake()->randomElement($amounts), 'unit' => fake()->randomElement($units)],
            ['id' => $salt->id, 'amount' => fake()->randomElement($amounts), 'unit' => fake()->randomElement($units)],
            ['id' => $pepper->id, 'amount' => fake()->randomElement($amounts), 'unit' => fake()->randomElement($units)],
            ['id' => $oliveOil->id, 'amount' => fake()->randomElement($amounts), 'unit' => fake()->randomElement($units)],
        ];
        foreach ($ingredients4 as $ingredient) {
            $recipe4->ingredients()->attach($ingredient['id'], [
                'amount' => $ingredient['amount'],
                'unit' => $ingredient['unit'],
            ]);
        }
        $steps4 = [
            ['description' => 'Boil potatoes until fork-tender', 'step_number' => 1],
            ['description' => 'Drain and let cool', 'step_number' => 2],
            ['description' => 'Cut potatoes into cubes', 'step_number' => 3],
            ['description' => 'Mix with salt, pepper, and olive oil', 'step_number' => 4],
            ['description' => 'Serve chilled or at room temperature', 'step_number' => 5],
        ];
        $recipe4->steps()->createMany($steps4);
    }
}
