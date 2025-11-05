<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'author_email',
        'slug',
        'steps',
    ];

    protected $casts = [
        'steps' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Recipe $recipe) {
            if (empty($recipe->slug)) {
                $recipe->slug = static::generateUniqueSlug($recipe->name);
            }
        });
    }

    public static function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;

        $count = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . ++$count;
        }

        return $slug;
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredients::class, 'ingredient_recipe', 'recipe_id', 'ingredient_id');
    }
}
