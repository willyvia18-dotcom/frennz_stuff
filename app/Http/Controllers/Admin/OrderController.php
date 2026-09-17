<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items')
            ->latest()
            ->get()
            ->map(fn ($o) => $this->toAdminArray($o));

        return view('admin.orders', compact('orders'));
    }

    public function updateStatus(Order $order, \Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,processing,shipped,completed,cancelled'],
        ]);

        $order->update(['status' => $data['status']]);

        return response()->json(['ok' => true, 'order' => $this->toAdminArray($order->fresh('items'))]);
    }

    private function toAdminArray(Order $o): array
    {
        return [
            'id' => $o->id,
            'buyer' => [
                'name' => $o->buyer_name,
                'phone' => $o->buyer_phone,
                'email' => $o->buyer_email,
            ],
            'address' => [
                'line' => $o->address_line,
                'city' => $o->address_city,
                'postal' => $o->address_postal,
            ],
            'date' => $o->created_at->toIso8601String(),
            'status' => $o->status,
            'shipping' => (float) $o->shipping_cost,
            'discount' => (float) $o->discount,
            'voucher' => $o->voucher_code,
            'total' => (float) $o->total,
            'items' => $o->items->map(fn ($it) => [
                'name' => $it->product_name_snapshot,
                'size' => $it->size,
                'color' => $it->color,
                'qty' => $it->qty,
                'price' => (float) $it->price,
            ])->values(),
        ];
    }
}