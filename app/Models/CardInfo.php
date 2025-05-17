<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'cardNumber',
        'expirationDate',
        'cvv',
        'paiement_id',
    ];

    protected $casts = [
        'expirationDate' => 'date',
    ];

    /**
     * Get the payment that owns the card info.
     */
    public function paiement(): BelongsTo
    {
        return $this->belongsTo(Paiement::class);
    }
}
