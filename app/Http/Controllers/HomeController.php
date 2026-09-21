<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = [
            [
                'id' => 1,
                'name' => 'Long Sleeve Polo Stone Island',
                'category' => 'T-Shirt',
                'brand' => 'Stone Island',
                'price' => 299000,
                'sale_price' => null,
                'image' => '/images/products/signature-tee-heavyweight-1.jpg',
                'label' => 'New'
            ],
            [
                'id' => 2,
                'name' => 'Adidas Adicolor Track Jacket',
                'category' => 'Hoodie',
                'brand' => 'Adidas',
                'price' => 649000,
                'sale_price' => null,
                'image' => '/images/products/track-jacket-minimal-1.jpg',
                'label' => 'Best Seller'
            ],
        ];

        return view('home.index', compact('featuredProducts'));
    }
}
