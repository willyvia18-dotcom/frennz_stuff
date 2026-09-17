<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'buyer_name', 'buyer_phone', 'buyer_email',
        'address_line', 'address_city', 'address_postal',
        'courier', 'payment_method', 'voucher_code',
        'shipping_cost', 'discount', 'total', 'status',
    ];

    protected $casts = [
        'shipping_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}