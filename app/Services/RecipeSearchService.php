<?php

namespace App\Services;

use App\Models\Recipe;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class RecipeSearchService implements RecipeSearchServiceInterface
{
    public function search(?string $email, ?string $keyword, ?array $ingredients, ?int $perPage = 15): LengthAwarePaginator
    {
        $query = Recipe::with(['ingredients', 'steps']);

        // Email - exact match
        if ($email) {
            $query->where('author_email', $email);
        }

        // Ingredients - recipes must have all selected ingredients (exact match on each)
        if ($ingredients && count($ingredients) > 0) {
            foreach ($ingredients as $ingredient) {
                if (! empty($ingredient)) {
                    $query->whereHas('ingredients', function (Builder $q) use ($ingredient) {
                        $q->where('name', $ingredient);
                    });
                }
            }
        }

        // Keyword - matches name, description, ingredients, or steps
        if ($keyword) {
            $query->where(function (Builder $q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('steps', function (Builder $stepQuery) use ($keyword) {
                        $stepQuery->where('description', 'LIKE', "%{$keyword}%");
                    })
                    ->orWhereHas('ingredients', function (Builder $ingredientQuery) use ($keyword) {
                        $ingredientQuery->where('name', 'LIKE', "%{$keyword}%");
                    });
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
