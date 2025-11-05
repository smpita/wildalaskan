<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RecipeSearchServiceInterface
{
    /**
     * Search recipes by email, keyword, and ingredients
     *
     * @param string|null $email
     * @param string|null $keyword
     * @param array|null $ingredients
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function search(?string $email, ?string $keyword, ?array $ingredients, ?int $perPage = 15): LengthAwarePaginator;
}

