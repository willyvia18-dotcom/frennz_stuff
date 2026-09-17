<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = [
            [
                'id' => 1,
                'name' => 'Oversized T-Shirt',
                'category' => 'T-Shirt',
                'price' => 169000,
                'sale_price' => null,
                'image' => 'https://images.unsplash.com/photo-1523398002811-999ca8dec234?auto=format&fit=crop&w=800&q=80',
                'label' => 'New'
            ],
            [
                'id' => 2,
                'name' => 'Streetwear Hoodie',
                'category' => 'Hoodie',
                'price' => 289000,
                'sale_price' => 249000,
                'image' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=800&q=80',
                'label' => 'Best Seller'
            ],
        ];

        return view('home.index', compact('featuredProducts'));
    }
}
