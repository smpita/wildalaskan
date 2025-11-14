<?php

namespace Tests\Feature;

use App\Models\Recipe;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRecipes();
    }

    private function seedRecipes(): void
    {
        $this->seed(DatabaseSeeder::class);
    }

    public function test_can_search_recipes_by_email(): void
    {
        $response = $this->getJson('/api/recipes?email=foo@bar.com');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['author_email' => 'foo@bar.com']);
    }

    public function test_can_search_recipes_by_keyword_in_name(): void
    {
        $response = $this->getJson('/api/recipes?keyword=scallop');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['name' => 'Scalloped Potatoes']);
        $response->assertJsonFragment(['name' => 'Seared Scallops with Potato Puree']);
    }

    public function test_can_search_recipes_by_keyword_in_description(): void
    {
        $response = $this->getJson('/api/recipes?keyword=weeknight');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['name' => 'Honey Glazed Salmon']);
    }

    public function test_can_search_recipes_by_keyword_in_steps(): void
    {
        $response = $this->getJson('/api/recipes?keyword=oven');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }

    public function test_can_search_recipes_by_ingredient_partial_match(): void
    {
        $response = $this->getJson('/api/recipes?ingredients[]=potato');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
        $response->assertJsonFragment(['name' => 'Scalloped Potatoes']);
        $response->assertJsonFragment(['name' => 'Seared Scallops with Potato Puree']);
        $response->assertJsonFragment(['name' => 'Simple Potato Salad']);
    }

    public function test_can_search_recipes_by_combination_email_and_ingredient(): void
    {
        $response = $this->getJson('/api/recipes?email=foo@bar.com&ingredients[]=potato');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['name' => 'Scalloped Potatoes']);
        $response->assertJsonFragment(['name' => 'Seared Scallops with Potato Puree']);
    }

    public function test_can_search_recipes_by_combination_email_and_keyword(): void
    {
        $response = $this->getJson('/api/recipes?email=foo@bar.com&keyword=scallop');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['name' => 'Scalloped Potatoes']);
        $response->assertJsonFragment(['name' => 'Seared Scallops with Potato Puree']);
    }

    public function test_can_search_recipes_by_combination_ingredient_and_keyword(): void
    {
        $response = $this->getJson('/api/recipes?ingredients[]=potato&keyword=scallop');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['name' => 'Scalloped Potatoes']);
        $response->assertJsonFragment(['name' => 'Seared Scallops with Potato Puree']);
    }

    public function test_can_search_recipes_by_all_three_parameters(): void
    {
        $response = $this->getJson('/api/recipes?email=foo@bar.com&ingredients[]=potato&keyword=scallop');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['name' => 'Scalloped Potatoes']);
        $response->assertJsonFragment(['name' => 'Seared Scallops with Potato Puree']);
    }

    public function test_search_returns_empty_when_no_matches(): void
    {
        $response = $this->getJson('/api/recipes?email=nonexistent@example.com');

        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
    }

    public function test_search_combination_returns_empty_when_no_matches(): void
    {
        $response = $this->getJson('/api/recipes?email=chef@example.com&ingredients[]=potato&keyword=scallop');

        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
    }

    public function test_can_get_recipe_by_id(): void
    {
        $recipe = Recipe::first();

        $response = $this->getJson("/api/recipes/{$recipe->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $recipe->id]);
        $response->assertJsonFragment(['name' => $recipe->name]);
    }

    public function test_can_get_recipe_by_slug(): void
    {
        $recipe = Recipe::first();

        $response = $this->getJson("/api/recipes/slug/{$recipe->slug}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['slug' => $recipe->slug]);
        $response->assertJsonFragment(['name' => $recipe->name]);
    }

    public function test_search_pagination_works(): void
    {
        $response = $this->getJson('/api/recipes?per_page=2');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'links',
            'meta',
        ]);
        $this->assertLessThanOrEqual(2, count($response->json('data')));
    }

    public function test_search_pagination_retains_search_parameters(): void
    {
        $response = $this->getJson('/api/recipes?email=foo@bar.com&ingredients[]=potato&keyword=scallop&per_page=1');

        $response->assertStatus(200);
        $links = $response->json('links');

        // Check that next page link contains search parameters
        $this->assertTrue(isset($links['next']));

        // Parse the URL to handle URL encoding
        $queryString = parse_url($links['next'], PHP_URL_QUERY);
        parse_str($queryString, $queryParams);

        $this->assertEquals('foo@bar.com', $queryParams['email'] ?? null);
        $this->assertEquals('potato', $queryParams['ingredients'][0] ?? null);
        $this->assertEquals('scallop', $queryParams['keyword'] ?? null);
    }
}
