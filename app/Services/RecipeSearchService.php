<?php

namespace App\Services;

use App\Models\Recipe;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class RecipeSearchService implements RecipeSearchServiceInterface
{
    public function search(?string $email, ?string $keyword, ?string $ingredient, ?int $perPage = 15): LengthAwarePaginator
    {
        $query = Recipe::with('ingredients');

        // Email - exact match
        if ($email) {
            $query->where('author_email', $email);
        }

        // Ingredient - partial match
        if ($ingredient) {
            $query->whereHas('ingredients', function (Builder $q) use ($ingredient) {
                $q->where('name', 'LIKE', "%{$ingredient}%");
            });
        }

        // Keyword - matches name, description, ingredients, or steps
        if ($keyword) {
            $query->where(function (Builder $q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%")
                    ->orWhereRaw('CAST(steps AS CHAR) LIKE ?', ["%{$keyword}%"])
                    ->orWhereHas('ingredients', function (Builder $ingredientQuery) use ($keyword) {
                        $ingredientQuery->where('name', 'LIKE', "%{$keyword}%");
                    });
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}

