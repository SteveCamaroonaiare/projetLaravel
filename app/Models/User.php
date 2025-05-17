<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
       'name',
        'email',
        'password',
        'verification_code',
        'verification_code_expires_at',
        'is_verified',
        'google_uid',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'verification_code_expires_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    /**
     * Get the cart associated with the user.
     */
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Get the comments for the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the commands for the user.
     */
    public function commands(): HasMany
    {
        return $this->hasMany(Command::class);
    }

    /**
     * Generate a verification code for the user.
     *
     * @return string
     */
    public function generateVerificationCode(): string
    {
        // Générer un code à 6 chiffres
        $verificationCode = (string) random_int(100000, 999999);
        
        // Définir la date d'expiration (24 heures)
        $expiresAt = Carbon::now()->addHours(24);
        
        // Mettre à jour l'utilisateur
        $this->update([
            'verification_code' => $verificationCode,
            'verification_code_expires_at' => $expiresAt,
        ]);
        
        return $verificationCode;
    }

    /**
     * Check if the verification code is valid.
     *
     * @param string $code
     * @return bool
     */
    public function isVerificationCodeValid(string $code): bool
    {
        return $this->verification_code === $code && 
               $this->verification_code_expires_at > Carbon::now();
    }

    /**
     * Mark the user as verified.
     *
     * @return void
     */
    public function markAsVerified(): void
    {
        $this->update([
            'is_verified' => true,
            'email_verified_at' => Carbon::now(),
            'verification_code' => null,
            'verification_code_expires_at' => null,
        ]);
    }
}
