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

        // If base slug is not taken, use it
        if (! static::where('slug', $slug)->exists()) {
            return $slug;
        }

        // Get existing slugs that start with base slug followed by a dash and number
        $matches = static::where('slug', 'LIKE', $slug . '-%')->get(['slug']);

        $maxCount = 0;
        foreach($matches as $match) {
            $end = explode($slug.'-', $match->slug)[1] ?? 0;
            if (is_numeric($end) && $end > $maxCount) {
                $maxCount = $end;
            }
        }

        // First increment is always -2 because it's the second use of the slug
        return $maxCount < 2
            ? $slug . '-2'
            : $slug . '-' . ++$maxCount;
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredients::class, 'ingredient_recipe', 'recipe_id', 'ingredient_id')
            ->withPivot('amount', 'unit');
    }

    public function steps()
    {
        return $this->hasMany(Step::class);
    }
}
