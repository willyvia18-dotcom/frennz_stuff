<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'description',
        'is_active', 'start_date', 'end_date',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('start_date')->orWhere('start_date', '<=', now()))
            ->where(fn ($q) => $q->whereNull('end_date')->orWhere('end_date', '>=', now()));
    }

    public function discountFor(float $subtotal): float
    {
        $discount = $this->type === 'percent'
            ? $subtotal * ((float) $this->value) / 100
            : (float) $this->value;

        return min($discount, $subtotal);
    }

    public function toAdminArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'type' => $this->type,
            'value' => (float) $this->value,
            'desc' => $this->description,
            'descEn' => $this->description,
            'active' => $this->is_active,
        ];
    }
}