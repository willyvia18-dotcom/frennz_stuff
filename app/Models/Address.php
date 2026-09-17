<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id', 'label', 'recipient_name', 'recipient_phone',
        'address_line', 'city', 'postal', 'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function toArrayPayload(): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'name' => $this->recipient_name,
            'phone' => $this->recipient_phone,
            'address' => $this->address_line,
            'city' => $this->city,
            'postal' => $this->postal,
            'isDefault' => $this->is_default,
        ];
    }
}