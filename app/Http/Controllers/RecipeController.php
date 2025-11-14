<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRecipesRequest;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use App\Services\RecipeSearchServiceInterface;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource with optional search.
     */
    public function index(SearchRecipesRequest $request, RecipeSearchServiceInterface $searchService)
    {
        $email = $request->validated('email');
        $keyword = $request->validated('keyword');
        $ingredients = $request->validated('ingredients');
        $perPage = $request->validated('per_page', 15);

        $recipes = $searchService->search($email, $keyword, $ingredients, $perPage);

        // Preserve query parameters in pagination links
        $recipes->appends($request->query());

        return RecipeResource::collection($recipes);
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        $recipe->load(['ingredients', 'steps']);

        return new RecipeResource($recipe);
    }

    /**
     * Display recipe by slug.
     */
    public function showBySlug(string $slug)
    {
        $recipe = Recipe::with(['ingredients', 'steps'])
            ->where('slug', $slug)->firstOrFail();

        return new RecipeResource($recipe);
    }
}
