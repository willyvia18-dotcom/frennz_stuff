<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class ReportController extends Controller
{
    public function index()
    {
        $revenue = (float) Order::where('status', '!=', 'cancelled')->sum('total');
        $ordersCount = Order::count();
        $done = Order::where('status', 'completed')->count();
        $cancelled = Order::where('status', 'cancelled')->count();

        $monthly = Order::where('status', '!=', 'cancelled')
            ->get()
            ->groupBy(fn ($o) => $o->created_at->month)
            ->map(fn ($g) => (float) $g->sum('total'));

        $monthTotals = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthTotals[] = (float) ($monthly[$m] ?? 0);
        }

        $topProducts = OrderItem::query()
            ->selectRaw('product_id, SUM(qty) as qty, SUM(subtotal) as revenue')
            ->groupBy('product_id')
            ->orderByDesc('revenue')
            ->take(8)
            ->get()
            ->map(function ($row) {
                $product = Product::with('category')->find($row->product_id);
                return [
                    'name' => $product->name ?? '—',
                    'category' => $product->category->name ?? '',
                    'qty' => (int) $row->qty,
                    'revenue' => (float) $row->revenue,
                ];
            })
            ->values();

        return view('admin.reports', compact(
            'revenue', 'ordersCount', 'done', 'cancelled', 'monthTotals', 'topProducts'
        ));
    }
}