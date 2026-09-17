<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        return response()->json(['ok' => true, 'addresses' => $this->payload()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $user = Auth::user();

        if (! empty($data['is_default']) || $user->addresses()->count() === 0) {
            $user->addresses()->update(['is_default' => false]);
            $data['is_default'] = true;
        }

        $address = $user->addresses()->create($data);

        return response()->json([
            'ok' => true,
            'address' => $address->toArrayPayload(),
            'addresses' => $this->payload(),
        ]);
    }

    public function update(Request $request, Address $address)
    {
        $this->authorizeOwner($address);

        $data = $this->validated($request);

        if (! empty($data['is_default'])) {
            $address->user->addresses()->update(['is_default' => false]);
        }

        $address->update($data);

        return response()->json([
            'ok' => true,
            'address' => $address->fresh()->toArrayPayload(),
            'addresses' => $this->payload(),
        ]);
    }

    public function destroy(Address $address)
    {
        $this->authorizeOwner($address);

        $wasDefault = $address->is_default;
        $user = $address->user;
        $address->delete();

        if ($wasDefault) {
            $next = $user->addresses()->latest()->first();
            if ($next) {
                $next->update(['is_default' => true]);
            }
        }

        return response()->json(['ok' => true, 'addresses' => $this->payload()]);
    }

    public function setDefault(Address $address)
    {
        $this->authorizeOwner($address);

        $address->user->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return response()->json(['ok' => true, 'addresses' => $this->payload()]);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'label' => ['nullable', 'string', 'max:60'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'recipient_phone' => ['required', 'string', 'max:30'],
            'address_line' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'postal' => ['required', 'string', 'max:12'],
            'is_default' => ['nullable', 'boolean'],
        ]);
    }

    private function authorizeOwner(Address $address): void
    {
        abort_unless($address->user_id === Auth::id(), 403);
    }

    private function payload()
    {
        return Auth::user()->addresses()
            ->orderByDesc('is_default')
            ->orderByDesc('updated_at')
            ->get()
            ->map->toArrayPayload()
            ->values();
    }
}