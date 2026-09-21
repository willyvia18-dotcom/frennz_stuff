<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $revenue = (float) Order::where('status', '!=', 'cancelled')->sum('total');
        $orderCount = Order::count();
        $productCount = Product::count();
        $customerCount = Order::distinct('buyer_email')->count('buyer_email');

        $categorySales = Category::all()->map(function ($cat) {
            $productIds = $cat->products()->pluck('id');
            $sum = OrderItem::whereIn('product_id', $productIds)->sum('subtotal');
            return ['name' => $cat->name, 'nameEn' => $cat->name_en, 'value' => (float) $sum];
        });

        $recentOrders = Order::latest()->take(6)->get()->map(fn ($o) => [
            'id' => $o->id,
            'buyer_name' => $o->buyer_name,
            'created_at' => $o->created_at->format('d M Y'),
            'total' => (float) $o->total,
            'status' => $o->status,
        ]);

        return view('admin.dashboard', compact(
            'revenue', 'orderCount', 'productCount', 'customerCount', 'categorySales', 'recentOrders'
        ));
    }
}