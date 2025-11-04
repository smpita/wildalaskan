<?php

namespace App\Console\Commands;

use App\Models\Ingredients;
use App\Models\Recipe;
use Illuminate\Console\Command;

class CreateRecipe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recipe:create
                            {--name= : Recipe name}
                            {--description= : Recipe description}
                            {--email= : Author email}
                            {--steps= : Comma-separated list of steps}
                            {--ingredients= : Comma-separated list of ingredient names}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new recipe in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->option('name') ?? $this->ask('Recipe name?');
        $description = $this->option('description') ?? $this->ask('Recipe description?', '');
        $email = $this->option('email') ?? $this->ask('Author email?');
        $stepsInput = $this->option('steps') ?? $this->ask('Steps (comma-separated)?', '');
        $ingredientsInput = $this->option('ingredients') ?? $this->ask('Ingredients (comma-separated)?', '');

        $steps = array_filter(array_map('trim', explode(',', $stepsInput)));
        $ingredientNames = array_filter(array_map('trim', explode(',', $ingredientsInput)));

        $recipe = Recipe::create([
            'name' => $name,
            'description' => $description,
            'author_email' => $email,
            'steps' => $steps,
        ]);

        // Attach ingredients
        $ingredientIds = [];
        foreach ($ingredientNames as $ingredientName) {
            $ingredient = Ingredients::firstOrCreate(['name' => $ingredientName]);
            $ingredientIds[] = $ingredient->id;
        }

        $recipe->ingredients()->attach($ingredientIds);

        $this->info("Recipe '{$recipe->name}' created successfully with slug: {$recipe->slug}");
        $this->info("Created with {$recipe->ingredients->count()} ingredients and " . count($steps) . ' steps.');

        return Command::SUCCESS;
    }
}

