<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'image',
        'dateOfSale',
        'percent',
        'numberOfSale',
        'reference',
        'category_id',
        'number0fStars',
        'sexes',
        'is_new',
        'is_trending',
        'is_promo'
    ];

    protected $casts = [
        'dateOfSale' => 'date',
        'price' => 'decimal:2',
        'percent' => 'decimal:2',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the variations for the product.
     */
    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    /**
     * Get the images for the product.
     */
    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    /**
     * Get the comments for the product.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the command details for the product.
     */
    public function commandDetails(): HasMany
    {
        return $this->hasMany(CommandDetail::class);
    }

    /**
     * The carts that belong to the product.
     */
    public function carts(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class)
            ->withPivot('quantity', 'product_variation_id')
            ->withTimestamps();
    }

    /**
     * The variable types that belong to the product.
     */
    public function variableTypes(): BelongsToMany
    {
        return $this->belongsToMany(VariableType::class);
    }
}
