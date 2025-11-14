<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\IngredientResource;
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
            'ingredients' => IngredientResource::collection($this->ingredients),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

