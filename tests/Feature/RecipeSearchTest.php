<?php

namespace Tests\Feature;

use App\Models\Ingredients;
use App\Models\Recipe;
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
        // Create ingredients
        $potato = Ingredients::create(['name' => '3 large potatoes']);
        $salt = Ingredients::create(['name' => '1 tsp salt']);
        $pepper = Ingredients::create(['name' => '1/2 tsp black pepper']);
        $butter = Ingredients::create(['name' => '2 tbsp butter']);
        $salmon = Ingredients::create(['name' => '1 lb salmon fillet']);
        $honey = Ingredients::create(['name' => '2 tbsp honey']);
        $scallops = Ingredients::create(['name' => '8 large scallops']);
        $oliveOil = Ingredients::create(['name' => '2 tbsp olive oil']);
        $garlic = Ingredients::create(['name' => '3 cloves garlic']);
        $lemon = Ingredients::create(['name' => '1 lemon']);

        // Recipe 1: Honey Glazed Salmon - chef@example.com
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

        // Recipe 2: Scalloped Potatoes - foo@bar.com
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

        // Recipe 3: Seared Scallops with Potato Puree - foo@bar.com
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

        // Recipe 4: Simple Potato Salad - chef@example.com
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
        if (isset($links['next'])) {
            // Parse the URL to handle URL encoding
            $queryString = parse_url($links['next'], PHP_URL_QUERY);
            parse_str($queryString, $queryParams);

            $this->assertEquals('foo@bar.com', $queryParams['email'] ?? null);
            $this->assertEquals('potato', $queryParams['ingredients'][0] ?? null);
            $this->assertEquals('scallop', $queryParams['keyword'] ?? null);
        }
    }
}

