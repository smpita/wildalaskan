<?php

namespace Tests\Unit;

use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class RecipeSlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_slug_is_generated_automatically_from_name(): void
    {
        $recipe = Recipe::create([
            'name' => 'Test Recipe',
            'description' => 'Test description',
            'author_email' => 'test@example.com',
            'steps' => ['Step 1', 'Step 2'],
        ]);

        $this->assertNotNull($recipe->slug);
        $this->assertEquals('test-recipe', $recipe->slug);
    }

    public function test_slug_generation_handles_special_characters(): void
    {
        $recipe = Recipe::create([
            'name' => 'Recipe with Special Characters! @#$%',
            'description' => 'Test description',
            'author_email' => 'test@example.com',
            'steps' => ['Step 1'],
        ]);

        $this->assertNotNull($recipe->slug);
        // Laravel's Str::slug converts @ to 'at', so we check for that
        $this->assertEquals('recipe-with-special-characters-at', $recipe->slug);
    }

    public function test_slug_generation_handles_unicode_characters(): void
    {
        $recipe = Recipe::create([
            'name' => 'Recette Française avec des accents',
            'description' => 'Test description',
            'author_email' => 'test@example.com',
            'steps' => ['Step 1'],
        ]);

        $this->assertNotNull($recipe->slug);
        $this->assertNotEmpty($recipe->slug);
    }

    public function test_explicitly_provided_slug_is_used(): void
    {
        $recipe = Recipe::create([
            'name' => 'Test Recipe',
            'description' => 'Test description',
            'author_email' => 'test@example.com',
            'slug' => 'custom-slug',
            'steps' => ['Step 1'],
        ]);

        $this->assertEquals('custom-slug', $recipe->slug);
    }

    public function test_duplicate_names_generate_unique_slugs(): void
    {
        $recipe1 = Recipe::create([
            'name' => 'Chocolate Cake',
            'description' => 'Test description',
            'author_email' => 'test@example.com',
            'steps' => ['Step 1'],
        ]);

        $recipe2 = Recipe::create([
            'name' => 'Chocolate Cake',
            'description' => 'Test description',
            'author_email' => 'test@example.com',
            'steps' => ['Step 1'],
        ]);

        $this->assertEquals('chocolate-cake', $recipe1->slug);
        $this->assertEquals('chocolate-cake-2', $recipe2->slug);
        $this->assertNotEquals($recipe1->slug, $recipe2->slug);
    }

    public function test_multiple_duplicate_names_generate_sequential_unique_slugs(): void
    {
        $recipe1 = Recipe::create([
            'name' => 'Pasta Recipe',
            'description' => 'Test description',
            'author_email' => 'test@example.com',
            'steps' => ['Step 1'],
        ]);

        $recipe2 = Recipe::create([
            'name' => 'Pasta Recipe',
            'description' => 'Test description',
            'author_email' => 'test@example.com',
            'steps' => ['Step 1'],
        ]);

        $recipe3 = Recipe::create([
            'name' => 'Pasta Recipe',
            'description' => 'Test description',
            'author_email' => 'test@example.com',
            'steps' => ['Step 1'],
        ]);

        $this->assertEquals('pasta-recipe', $recipe1->slug);
        $this->assertEquals('pasta-recipe-2', $recipe2->slug);
        $this->assertEquals('pasta-recipe-3', $recipe3->slug);
        $this->assertCount(3, array_unique([$recipe1->slug, $recipe2->slug, $recipe3->slug]));
    }

    public function test_slug_uniqueness_is_enforced_at_database_level(): void
    {
        Recipe::create([
            'name' => 'Unique Recipe',
            'description' => 'Test description',
            'author_email' => 'test@example.com',
            'slug' => 'unique-recipe',
            'steps' => ['Step 1'],
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Recipe::create([
            'name' => 'Another Recipe',
            'description' => 'Test description',
            'author_email' => 'test@example.com',
            'slug' => 'unique-recipe', // Same slug
            'steps' => ['Step 1'],
        ]);
    }

    public function test_generate_unique_slug_method_creates_unique_slugs(): void
    {
        $name = 'Test Recipe Name';

        $slug1 = Recipe::generateUniqueSlug($name);
        $this->assertEquals('test-recipe-name', $slug1);

        // Create a recipe with the generated slug
        Recipe::create([
            'name' => $name,
            'description' => 'Test description',
            'author_email' => 'test@example.com',
            'slug' => $slug1,
            'steps' => ['Step 1'],
        ]);

        // Generate another slug with the same name
        $slug2 = Recipe::generateUniqueSlug($name);
        $this->assertEquals('test-recipe-name-2', $slug2);
        $this->assertNotEquals($slug1, $slug2);
    }

    public function test_slug_uniqueness_with_existing_numbered_slugs(): void
    {
        // Create recipes with explicit numbered slugs
        Recipe::create([
            'name' => 'Recipe One',
            'description' => 'Test description',
            'author_email' => 'test@example.com',
            'slug' => 'recipe-name-2',
            'steps' => ['Step 1'],
        ]);

        Recipe::create([
            'name' => 'Recipe Two',
            'description' => 'Test description',
            'author_email' => 'test@example.com',
            'slug' => 'recipe-name-3',
            'steps' => ['Step 1'],
        ]);

        // Now create a recipe with the same name - should generate recipe-name (first available)
        $recipe = Recipe::create([
            'name' => 'Recipe Name',
            'description' => 'Test description',
            'author_email' => 'test@example.com',
            'steps' => ['Step 1'],
        ]);

        // The generateUniqueSlug method should find recipe-name doesn't exist
        // and create it since recipe-name-2 and recipe-name-3 exist but recipe-name doesn't
        $this->assertEquals('recipe-name', $recipe->slug);
    }

    public function test_slug_is_not_regenerated_on_update(): void
    {
        $recipe = Recipe::create([
            'name' => 'Original Recipe',
            'description' => 'Test description',
            'author_email' => 'test@example.com',
            'steps' => ['Step 1'],
        ]);

        $originalSlug = $recipe->slug;
        $this->assertEquals('original-recipe', $originalSlug);

        $recipe->update([
            'name' => 'Updated Recipe Name',
        ]);

        $recipe->refresh();
        $this->assertEquals($originalSlug, $recipe->slug);
    }
}

