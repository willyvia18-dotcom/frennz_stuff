<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Order::query()
            ->selectRaw('buyer_email, MAX(buyer_name) as name, MAX(buyer_phone) as phone, COUNT(*) as orders, SUM(total) as spend')
            ->groupBy('buyer_email')
            ->orderByDesc('spend')
            ->get()
            ->map(fn ($c) => [
                'name' => $c->name,
                'email' => $c->buyer_email,
                'phone' => $c->phone,
                'orders' => (int) $c->orders,
                'spend' => (float) $c->spend,
            ])
            ->values();

        return view('admin.customers', compact('customers'));
    }
}