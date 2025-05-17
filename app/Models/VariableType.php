<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VariableType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'variable_id',
    ];

    /**
     * Get the variable that owns the variable type.
     */
    public function variable(): BelongsTo
    {
        return $this->belongsTo(Variable::class);
    }

    /**
     * The products that belong to the variable type.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * The product variations that belong to the variable type.
     */
    public function productVariations(): BelongsToMany
    {
        return $this->belongsToMany(ProductVariation::class);
    }
}
