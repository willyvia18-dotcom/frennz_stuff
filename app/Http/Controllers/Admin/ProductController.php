<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = $this->allProducts();
        $categories = Category::all()->map->toStorefrontArray()->values();

        return view('admin.products', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        return $this->save($request, new Product());
    }

    public function update(Request $request, Product $product)
    {
        return $this->save($request, $product);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['ok' => true]);
    }

    private function save(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:50'],
            'category' => ['required', 'string'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'sizes' => ['required', 'string'],
            'stock_list' => ['required', 'string'],
            'colors' => ['required', 'string'],
            'images' => ['required', 'string'],
            'desc' => ['nullable', 'string'],
            'desc_en' => ['nullable', 'string'],
            'tag' => ['nullable', 'string'],
            'bestseller' => ['nullable', 'boolean'],
        ]);

        $category = Category::firstOrCreate(
            ['slug' => Str::slug($data['category'])],
            ['name' => $data['category'], 'icon' => '🛍️']
        );

        if (! $product->exists) {
            $product->slug = Str::slug($data['name']) . '-' . Str::random(6);
            $product->reviews_count = 0;
        }

        $product->fill([
            'category_id' => $category->id,
            'name' => $data['name'],
            'brand' => $data['brand'] ?? null,
            'price' => $data['price'],
            'sale_price' => $data['sale_price'] ?: null,
            'description' => $data['desc'] ?? '',
            'description_en' => $data['desc_en'] ?? null,
            'rating' => $data['rating'] ?? 4.5,
            'is_new' => $data['tag'] === 'NEW',
            'is_best_seller' => (bool) ($data['bestseller'] ?? false),
            'status' => 'published',
        ]);

        DB::transaction(function () use ($product, $data) {
            $product->save();

            $sizes = array_values(array_filter(array_map('trim', explode(',', $data['sizes']))));
            $stocks = array_map('trim', explode(',', $data['stock_list']));
            $colors = array_values(array_filter(array_map(function ($pair) {
                [$name, $hex] = array_pad(explode(':', trim($pair)), 2, '#cccccc');
                return ['name' => trim($name), 'hex' => trim($hex)];
            }, explode(',', $data['colors']))));
            $images = array_values(array_filter(array_map('trim', explode(',', $data['images']))));

            $product->variants()->delete();
            foreach ($sizes as $i => $size) {
                $stock = (int) ($stocks[$i] ?? 0);
                foreach ($colors as $j => $color) {
                    $product->variants()->create([
                        'size' => $size,
                        'color_name' => $color['name'],
                        'color_hex' => $color['hex'],
                        'stock' => $stock,
                        'sku' => 'FZ-' . Str::upper(Str::random(6)) . '-' . $i . $j,
                    ]);
                }
            }

            $product->images()->delete();
            foreach ($images as $i => $url) {
                $product->images()->create(['image_path' => $url, 'sort_order' => $i]);
            }
        });

        return response()->json(['ok' => true, 'products' => $this->allProducts()]);
    }

    private function allProducts()
    {
        return Product::with(['category', 'images', 'variants'])
            ->get()
            ->map->toStorefrontArray()
            ->values();
    }
}