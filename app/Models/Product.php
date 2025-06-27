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
        'sexes',
        'is_new',
        'is_trending',
        'is_promo',
        'percent',
        'numberOfStars',
        'color_variants'
    ];

    protected $casts = [
        'is_new' => 'boolean',
        'is_trending' => 'boolean',
        'is_promo' => 'boolean',
        'price' => 'decimal:2',
        'percent' => 'decimal:2',
        'color_variants' => 'array'
    ];


    public function orders()
{
    return $this->belongsToMany(Order::class)->withPivot('quantity', 'price')->withTimestamps();
}

    // Accessor pour l'URL de l'image
    public function getImageUrlAttribute()
    {
        if (!$this->image) return null;
        
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        return asset('storage/' . $this->image);
    }
    // Calcul du prix promo
    public function getPromoPriceAttribute()
    {
        return $this->is_promo 
            ? round($this->price * (1 - $this->percent / 100), 2)
            : null;
    }

    


// Dans app/Models/Product.php

// Remplacez l'accesseur existant par :
public function getColorVariants()
{
    if (empty($this->color_variants)) {
        return [];
    }
    
    $decoded = json_decode($this->color_variants, true);
    
    return is_array($decoded) ? $decoded : [];
}

// Ajoutez ceci pour garder l'image originale accessible
public function getOriginalImageUrlAttribute()
{
    return Storage::url($this->image);
}

public function getColorImageUrlAttribute()
{
    return function ($colorSuffix) {
        if (empty($colorSuffix)) {
            return $this->original_image_url;
        }
        
        $path = str_replace('.jpg', "-{$colorSuffix}.jpg", $this->image);
        
        return Storage::exists($path) 
            ? Storage::url($path)
            : $this->original_image_url;
    };
}



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


    public function colors()
{
    return $this->belongsToMany(Color::class)->withPivot('image_url');
}

public function sizes()
{
    return $this->belongsToMany(Size::class)->withPivot('stock');
}

public function mainImages()
{
    return $this->hasMany(Image::class)->where('type', 'main');
}

public function thumbnailImages()
{
    return $this->hasMany(Image::class)->where('type', 'thumbnail');
}


public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }



    
}


