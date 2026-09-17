<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        return view('admin.promos', ['vouchers' => $this->all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:60', 'unique:vouchers,code'],
            'type' => ['required', 'in:percent,fixed'],
            'value' => ['required', 'numeric', 'min:0'],
            'desc' => ['nullable', 'string', 'max:255'],
            'descEn' => ['nullable', 'string', 'max:255'],
        ]);

        Voucher::create([
            'code' => strtoupper($data['code']),
            'type' => $data['type'],
            'value' => $data['value'],
            'description' => $data['desc'] ?? null,
            'is_active' => true,
        ]);

        return response()->json(['ok' => true, 'vouchers' => $this->all()]);
    }

    public function toggle(Voucher $voucher)
    {
        $voucher->update(['is_active' => ! $voucher->is_active]);

        return response()->json(['ok' => true, 'vouchers' => $this->all()]);
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();

        return response()->json(['ok' => true, 'vouchers' => $this->all()]);
    }

    private function all()
    {
        return Voucher::latest()->get()->map->toAdminArray()->values();
    }
}