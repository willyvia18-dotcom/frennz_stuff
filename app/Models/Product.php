<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'price', 'sale_price',
        'description', 'description_en', 'rating', 'reviews_count',
        'is_new', 'is_best_seller', 'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_new' => 'boolean',
        'is_best_seller' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Bentuk array yang dipakai front-end — sama persis dengan struktur
     * DEFAULT_PRODUCTS di public/js/data.js, supaya JS filter/sort/cart
     * yang sudah ada jalan tanpa perlu ditulis ulang.
     */
    public function toStorefrontArray(): array
    {
        $sizeGroups = $this->variants->groupBy('size');

        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'category' => $this->category->name ?? '',
            'price' => (float) $this->price,
            'salePrice' => $this->sale_price !== null ? (float) $this->sale_price : null,
            'colors' => $this->variants
                ->unique('color_name')
                ->filter(fn ($v) => $v->color_name)
                ->map(fn ($v) => ['name' => $v->color_name, 'hex' => $v->color_hex])
                ->values(),
            'sizes' => $sizeGroups->keys()->filter()->values(),
            'stock' => $sizeGroups->map(fn ($g) => (int) $g->max('stock')),
            'rating' => (float) $this->rating,
            'reviews' => $this->reviews_count,
            'desc' => $this->description,
            'descEn' => $this->description_en,
            'images' => $this->images->pluck('image_path')->values(),
            'tag' => $this->sale_price ? 'SALE' : ($this->is_new ? 'NEW' : null),
            'bestseller' => (bool) $this->is_best_seller,
        ];
    }
}