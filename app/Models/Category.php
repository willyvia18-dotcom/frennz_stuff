<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'name_en', 'slug', 'icon'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function toStorefrontArray(): array
    {
        return [
            'name' => $this->name,
            'en' => $this->name_en,
            'icon' => $this->icon,
        ];
    }
}