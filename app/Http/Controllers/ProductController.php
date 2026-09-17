<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        [$products, $categories] = $this->storefrontData();

        return view('products.index', compact('products', 'categories'));
    }

    public function show()
    {
        [$products, $categories] = $this->storefrontData();

        return view('products.show', compact('products', 'categories'));
    }

    private function storefrontData(): array
    {
        $products = Product::with(['category', 'images', 'variants'])
            ->published()
            ->get()
            ->map->toStorefrontArray()
            ->values();

        $categories = Category::all()->map->toStorefrontArray()->values();

        return [$products, $categories];
    }
}