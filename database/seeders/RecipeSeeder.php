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
        $potato = Ingredients::where('name', '3 large potatoes')->firstOrFail();
        $salt = Ingredients::where('name', '1 tsp salt')->firstOrFail();
        $pepper = Ingredients::where('name', '1/2 tsp black pepper')->firstOrFail();
        $butter = Ingredients::where('name', '2 tbsp butter')->firstOrFail();
        $salmon = Ingredients::where('name', '1 lb salmon fillet')->firstOrFail();
        $honey = Ingredients::where('name', '2 tbsp honey')->firstOrFail();
        $scallops = Ingredients::where('name', '8 large scallops')->firstOrFail();
        $oliveOil = Ingredients::where('name', '2 tbsp olive oil')->firstOrFail();
        $garlic = Ingredients::where('name', '3 cloves garlic')->firstOrFail();
        $lemon = Ingredients::where('name', '1 lemon')->firstOrFail();

        // Recipe 1: Honey Glazed Salmon
        $recipe1 = Recipe::create([
            'name' => 'Honey Glazed Salmon',
            'description' => 'A delicious and healthy salmon dish with a sweet honey glaze. Perfect for a weeknight dinner.',
            'author_email' => 'chef@example.com',
            'steps' => [
                'Preheat oven to 400°F',
                'Place salmon fillet on a baking sheet',
                'Mix honey, olive oil, and garlic in a small bowl',
                'Brush the honey mixture over the salmon',
                'Bake for 12-15 minutes until fish flakes easily',
                'Serve with lemon wedges'
            ],
        ]);
        $recipe1->ingredients()->attach([$salmon->id, $honey->id, $oliveOil->id, $garlic->id, $lemon->id]);

        // Recipe 2: Scalloped Potatoes
        $recipe2 = Recipe::create([
            'name' => 'Scalloped Potatoes',
            'description' => 'Creamy, cheesy scalloped potatoes that are perfect as a side dish. The scalloped potatoes are layered with a rich sauce.',
            'author_email' => 'foo@bar.com',
            'steps' => [
                'Preheat oven to 375°F',
                'Slice potatoes into thin rounds',
                'Layer potatoes in a baking dish',
                'Melt butter and mix with salt and pepper',
                'Pour butter mixture over potatoes',
                'Bake for 45 minutes until tender and golden'
            ],
        ]);
        $recipe2->ingredients()->attach([$potato->id, $salt->id, $pepper->id, $butter->id]);

        // Recipe 3: Seared Scallops with Potato Puree
        $recipe3 = Recipe::create([
            'name' => 'Seared Scallops with Potato Puree',
            'description' => 'Gourmet seared scallops served over creamy potato puree. A restaurant-quality dish with scallops and potatoes.',
            'author_email' => 'foo@bar.com',
            'steps' => [
                'Boil potatoes until tender',
                'Mash potatoes with butter, salt, and pepper',
                'Heat olive oil in a large skillet',
                'Season scallops with salt and pepper',
                'Sear scallops for 2-3 minutes per side until golden',
                'Serve scallops over potato puree'
            ],
        ]);
        $recipe3->ingredients()->attach([$scallops->id, $potato->id, $butter->id, $salt->id, $pepper->id, $oliveOil->id]);

        // Recipe 4: Simple Potato Salad
        $recipe4 = Recipe::create([
            'name' => 'Simple Potato Salad',
            'description' => 'A classic potato salad recipe that goes great with any barbecue or picnic.',
            'author_email' => 'chef@example.com',
            'steps' => [
                'Boil potatoes until fork-tender',
                'Drain and let cool',
                'Cut potatoes into cubes',
                'Mix with salt, pepper, and olive oil',
                'Serve chilled or at room temperature'
            ],
        ]);
        $recipe4->ingredients()->attach([$potato->id, $salt->id, $pepper->id, $oliveOil->id]);
    }
}

