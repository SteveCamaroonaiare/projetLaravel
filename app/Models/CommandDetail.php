<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommandDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'unitPrice',
        'command_id',
        'product_id',
        'product_variation_id',
    ];

    protected $casts = [
        'unitPrice' => 'decimal:2',
    ];

    /**
     * Get the command that owns the detail.
     */
    public function command(): BelongsTo
    {
        return $this->belongsTo(Command::class);
    }

    /**
     * Get the product that owns the detail.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product variation that owns the detail.
     */
    public function productVariation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class);
    }
}
