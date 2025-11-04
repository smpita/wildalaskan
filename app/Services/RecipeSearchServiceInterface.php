<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RecipeSearchServiceInterface
{
    /**
     * Search recipes by email, keyword, and ingredient
     *
     * @param string|null $email
     * @param string|null $keyword
     * @param string|null $ingredient
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function search(?string $email, ?string $keyword, ?string $ingredient, ?int $perPage = 15): LengthAwarePaginator;
}

