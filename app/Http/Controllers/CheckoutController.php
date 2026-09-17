<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('checkout.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'postal' => ['required', 'string', 'max:12'],
            'courier' => ['required', 'in:Reguler,Express'],
            'payment' => ['required', 'in:Transfer Bank,E-Wallet,COD'],
            'voucher_code' => ['nullable', 'string'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'address_id' => ['nullable', 'integer'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer'],
            'items.*.size' => ['nullable', 'string'],
            'items.*.color' => ['nullable', 'string'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $order = DB::transaction(function () use ($data) {
                $subtotal = 0;
                $lineItems = [];

                foreach ($data['items'] as $line) {
                    $product = Product::with('variants')->find($line['product_id']);
                    if (! $product) {
                        throw new \RuntimeException(__('ui.messages.product_gone'));
                    }

                    $variant = $product->variants
                        ->where('size', $line['size'])
                        ->where('color_name', $line['color'])
                        ->first();

                    if (! $variant || $variant->stock < $line['qty']) {
                        throw new \RuntimeException(__('ui.messages.stock_product', ['product' => $product->name, 'size' => $line['size']]));
                    }

                    $variant->decrement('stock', $line['qty']);

                    $price = (float) ($product->sale_price ?? $product->price);
                    $subtotal += $price * $line['qty'];

                    $lineItems[] = [
                        'product_id' => $product->id,
                        'product_name_snapshot' => $product->name,
                        'size' => $line['size'],
                        'color' => $line['color'],
                        'qty' => $line['qty'],
                        'price' => $price,
                        'subtotal' => $price * $line['qty'],
                    ];
                }

                $shippingCost = $data['courier'] === 'Express'
                    ? 45000
                    : ($subtotal >= 300000 ? 0 : 20000);

                $discount = min((float) ($data['discount'] ?? 0), $subtotal);
                $total = max(0, $subtotal + $shippingCost - $discount);

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'buyer_name' => $data['name'],
                    'buyer_phone' => $data['phone'],
                    'buyer_email' => $data['email'],
                    'address_line' => $data['address'],
                    'address_city' => $data['city'],
                    'address_postal' => $data['postal'],
                    'courier' => $data['courier'],
                    'payment_method' => $data['payment'],
                    'voucher_code' => $data['voucher_code'] ?? null,
                    'shipping_cost' => $shippingCost,
                    'discount' => $discount,
                    'total' => $total,
                    'status' => 'pending',
                ]);

                foreach ($lineItems as $item) {
                    $order->items()->create($item);
                }

                if (Auth::check()) {
                    Auth::user()->cartItems()->delete();
                    $this->rememberBuyerData($data);
                }

                return $order;
            });
        } catch (\RuntimeException $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json(['ok' => true, 'orderId' => $order->id]);
    }

    private function rememberBuyerData(array $data): void
    {
        $user = Auth::user();

        if (! $user->phone) {
            $user->phone = $data['phone'];
            $user->save();
        }

        if (! empty($data['address_id'])) {
            $address = $user->addresses()->find($data['address_id']);
            if ($address) {
                $address->update([
                    'recipient_name' => $data['name'],
                    'recipient_phone' => $data['phone'],
                    'address_line' => $data['address'],
                    'city' => $data['city'],
                    'postal' => $data['postal'],
                ]);
                return;
            }
        }

        $user->addresses()->firstOrCreate(
            [
                'address_line' => $data['address'],
                'city' => $data['city'],
                'postal' => $data['postal'],
            ],
            [
                'recipient_name' => $data['name'],
                'recipient_phone' => $data['phone'],
                'is_default' => $user->addresses()->count() === 0,
            ]
        );
    }
}