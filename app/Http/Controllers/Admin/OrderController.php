<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

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

        if (in_array($order->status, ['completed', 'cancelled'], true)) {
            return response()->json(['ok' => false, 'message' => __('ui.messages.order_locked')], 422);
        }

        DB::transaction(function () use ($order, $data) {
            // Pembatalan mengembalikan stok varian yang dipesan.
            if ($data['status'] === 'cancelled') {
                foreach ($order->items as $item) {
                    ProductVariant::where('product_id', $item->product_id)
                        ->where('size', $item->size)
                        ->where('color_name', $item->color)
                        ->increment('stock', $item->qty);
                }
            }

            $order->update(['status' => $data['status']]);
        });

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