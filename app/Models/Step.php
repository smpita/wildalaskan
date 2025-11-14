<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'step_number',
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
