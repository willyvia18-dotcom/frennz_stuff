<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = $this->allCategories();

        return view('admin.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:8'],
        ]);

        Category::create([
            'name' => $data['name'],
            'name_en' => $data['name_en'] ?: null,
            'slug' => Str::slug($data['name']) . '-' . Str::random(4),
            'icon' => $data['icon'] ?: '📦',
        ]);

        return response()->json(['ok' => true, 'categories' => $this->allCategories()]);
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return response()->json([
                'ok' => false,
                'message' => __('ui.messages.cat_in_use'),
            ], 422);
        }

        $category->delete();

        return response()->json(['ok' => true, 'categories' => $this->allCategories()]);
    }

    private function allCategories()
    {
        return Category::withCount('products')->get()->map(fn ($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'en' => $c->name_en,
            'icon' => $c->icon,
            'productCount' => $c->products_count,
        ])->values();
    }
}