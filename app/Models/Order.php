<?php

// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Order extends Model {
    use Notifiable;
}

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'shipping_address',
        'email',
        'phone',
        'payment_method',
        'status',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price')->withTimestamps();
    }
}
