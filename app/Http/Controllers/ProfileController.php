<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $orders = $user->orders()
            ->with('items')
            ->latest()
            ->get()
            ->map(fn ($o) => [
                'id' => $o->id,
                'date' => $o->created_at->toIso8601String(),
                'status' => $o->status,
                'total' => (float) $o->total,
                'shipping' => (float) $o->shipping_cost,
                'discount' => (float) $o->discount,
                'voucher' => $o->voucher_code,
                'courier' => $o->courier,
                'payment' => $o->payment_method,
                'address' => [
                    'line' => $o->address_line,
                    'city' => $o->address_city,
                    'postal' => $o->address_postal,
                ],
                'items' => $o->items->map(fn ($it) => [
                    'name' => $it->product_name_snapshot,
                    'size' => $it->size,
                    'color' => $it->color,
                    'qty' => $it->qty,
                    'price' => (float) $it->price,
                ])->values(),
            ])
            ->values();

        $addresses = $user->addresses()
            ->orderByDesc('is_default')
            ->orderByDesc('updated_at')
            ->get()
            ->map->toArrayPayload()
            ->values();

        $stats = [
            'orders' => $orders->count(),
            'spend' => (float) $orders->where('status', '!=', 'cancelled')->sum('total'),
            'active' => $orders->whereIn('status', ['pending', 'processing', 'shipped'])->count(),
        ];

        return view('profile.index', [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'initial' => strtoupper(mb_substr($user->name, 0, 1)),
            ],
            'orders' => $orders,
            'addresses' => $addresses,
            'stats' => $stats,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        return response()->json(['ok' => true, 'message' => __('ui.profile.updated')]);
    }
}