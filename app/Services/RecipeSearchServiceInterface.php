<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RecipeSearchServiceInterface
{
    /**
     * Search recipes by email, keyword, and ingredients
     */
    public function search(?string $email, ?string $keyword, ?array $ingredients, ?int $perPage = 15): LengthAwarePaginator;
}
