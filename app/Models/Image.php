<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'imageUrl',
        'isPrincipal',
        'product_id',
        'category_id',
        'image'
    ];

    protected $casts = [
        'isPrincipal' => 'boolean',
    ];

    /**
     * Get the product that owns the image.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the category that owns the image.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
