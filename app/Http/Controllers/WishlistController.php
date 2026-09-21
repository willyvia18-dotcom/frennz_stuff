<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function toggle(Request $request)
    {
        $data = $request->validate(['product_id' => ['required', 'integer', 'exists:products,id']]);

        $existing = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $data['product_id'])
            ->first();

        if ($existing) {
            $existing->delete();
            $added = false;
        } else {
            Wishlist::create(['user_id' => Auth::id(), 'product_id' => $data['product_id']]);
            $added = true;
        }

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->pluck('product_id')
            ->map(fn ($id) => (string) $id)
            ->values();

        return response()->json(['ok' => true, 'added' => $added, 'wishlist' => $wishlist]);
    }
}