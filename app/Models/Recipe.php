<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Recipe extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title',
        'description',
        'instructions',
        'prep_time',
        'cook_time',
        'servings',
        'image',
        'user_id',
        'category_id',
    ];

    // Pieder lietotājam
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Pieder kategorijai
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Daudz sastāvdaļu caur starp tabulu
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredient')
                    ->withPivot('amount')
                    ->withTimestamps();
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
