<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;
    protected $fillable = [
        'eu_size',
    ];

    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'product_variant_size')
            ->withPivot('stock');
    }
}
