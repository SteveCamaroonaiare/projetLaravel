<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Command extends Model
{
    use HasFactory;

    protected $fillable = [
        'commandDate',
        'status',
        'user_id',
    ];

    protected $casts = [
        'commandDate' => 'datetime',
    ];

    /**
     * Get the user that owns the command.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the details for the command.
     */
    public function details(): HasMany
    {
        return $this->hasMany(CommandDetail::class);
    }

    /**
     * Get the payments for the command.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }
}
