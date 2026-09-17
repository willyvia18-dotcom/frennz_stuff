<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $products = Product::with(['category', 'images', 'variants'])
                ->published()
                ->get()
                ->map->toStorefrontArray()
                ->values();

            $categories = Category::all()->map->toStorefrontArray()->values();

            $cartItems = collect();
            if (Auth::check()) {
                $cartItems = Auth::user()->cartItems()
                    ->with('variant')
                    ->get()
                    ->map(fn ($item) => [
                        'cartItemId' => $item->id,
                        'productId' => (string) $item->variant->product_id,
                        'size' => $item->variant->size,
                        'color' => $item->variant->color_name,
                        'qty' => $item->quantity,
                    ])
                    ->values();
            }
            $wishlist = collect();
                if (Auth::check()) {
                $wishlist = Auth::user()->wishlists()->pluck('product_id')->map(fn ($id) => (string) $id)->values();
            }

            $addresses = collect();
            $buyer = null;
            if (Auth::check()) {
                $addresses = Auth::user()->addresses()
                    ->orderByDesc('is_default')
                    ->orderByDesc('updated_at')
                    ->get()
                    ->map->toArrayPayload()
                    ->values();

                $buyer = [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->phone,
                ];
            }

            $view->with([
                'serverProducts' => $products,
                'serverCategories' => $categories,
                'serverCartItems' => $cartItems,
                'isAuthenticated' => Auth::check(),
                'serverWishlist' => $wishlist,
                'serverAddresses' => $addresses,
                'buyerProfile' => $buyer,
            ]);
        });
    }
}