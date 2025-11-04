<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'author_email' => $this->author_email,
            'slug' => $this->slug,
            'steps' => $this->steps,
            'ingredients' => $this->ingredients->map(function ($ingredient) {
                return [
                    'id' => $ingredient->id,
                    'name' => $ingredient->name,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

