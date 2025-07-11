<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    /**
     * Get the payments for the currency.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }
}
