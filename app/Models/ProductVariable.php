<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'isAvailable',
        'product_id',
    ];

    protected $casts = [
        'isAvailable' => 'boolean',
    ];

    /**
     * Get the product that owns the variation.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * The variable types that belong to the product variation.
     */
    public function variableTypes(): BelongsToMany
    {
        return $this->belongsToMany(VariableType::class);
    }

    /**
     * Get the command details for the product variation.
     */
    public function commandDetails(): HasMany
    {
        return $this->hasMany(CommandDetail::class);
    }
}
