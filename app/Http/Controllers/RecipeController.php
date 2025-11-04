<?php

namespace App\Http\Controllers;

use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use App\Services\RecipeSearchServiceInterface;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function __construct(
        private RecipeSearchServiceInterface $searchService
    ) {
    }

    /**
     * Display a listing of the resource with optional search.
     */
    public function index(Request $request)
    {
        $email = $request->input('email');
        $keyword = $request->input('keyword');
        $ingredient = $request->input('ingredient');
        $perPage = $request->input('per_page', 15);

        $recipes = $this->searchService->search($email, $keyword, $ingredient, $perPage);

        // Preserve query parameters in pagination links
        $recipes->appends($request->query());

        return RecipeResource::collection($recipes);
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        $recipe->load('ingredients');

        return new RecipeResource($recipe);
    }

    /**
     * Display recipe by slug.
     */
    public function showBySlug(string $slug)
    {
        $recipe = Recipe::with('ingredients')->where('slug', $slug)->firstOrFail();

        return new RecipeResource($recipe);
    }
}
