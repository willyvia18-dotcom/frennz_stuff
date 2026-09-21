<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'size' => ['nullable', 'string'],
            'color' => ['nullable', 'string'],
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        $variant = ProductVariant::where('product_id', $data['product_id'])
            ->where('size', $data['size'])
            ->where('color_name', $data['color'])
            ->first();

        if (! $variant) {
            return response()->json(['ok' => false, 'message' => __('ui.messages.variant_missing')], 404);
        }

        $item = CartItem::firstOrNew([
            'user_id' => Auth::id(),
            'product_variant_id' => $variant->id,
        ]);
        $newQty = ($item->exists ? $item->quantity : 0) + $data['qty'];

        if ($newQty > $variant->stock) {
            return response()->json(['ok' => false, 'message' => __('ui.messages.stock_insufficient')], 422);
        }

        $item->quantity = $newQty;
        $item->save();

        return response()->json(['ok' => true, 'cart' => $this->cartPayload()]);
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $this->authorizeOwner($cartItem);

        $data = $request->validate(['qty' => ['required', 'integer', 'min:1']]);

        if ($data['qty'] > $cartItem->variant->stock) {
            return response()->json(['ok' => false, 'message' => __('ui.messages.stock_insufficient')], 422);
        }

        $cartItem->update(['quantity' => $data['qty']]);

        return response()->json(['ok' => true, 'cart' => $this->cartPayload()]);
    }

    public function destroy(CartItem $cartItem)
    {
        $this->authorizeOwner($cartItem);
        $cartItem->delete();

        return response()->json(['ok' => true, 'cart' => $this->cartPayload()]);
    }

    private function authorizeOwner(CartItem $cartItem): void
    {
        abort_unless($cartItem->user_id === Auth::id(), 403);
    }

    private function cartPayload()
    {
        return Auth::user()->cartItems()
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
}