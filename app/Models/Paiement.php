<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'status',
        'paiementDate',
        'paiementMethod',
        'command_id',
        'currency_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paiementDate' => 'datetime',
    ];

    /**
     * Get the command that owns the payment.
     */
    public function command(): BelongsTo
    {
        return $this->belongsTo(Command::class);
    }

    /**
     * Get the currency that owns the payment.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Get the card info associated with the payment.
     */
    public function cardInfo(): HasOne
    {
        return $this->hasOne(CardInfo::class);
    }
}
