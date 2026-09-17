<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Hoodie', 'name_en' => 'Hoodies', 'icon' => '🧥'],
            ['name' => 'T-Shirt', 'name_en' => 'T-Shirts', 'icon' => '👕'],
            ['name' => 'Crewneck', 'name_en' => 'Crewnecks', 'icon' => '🎽'],
            ['name' => 'Kemeja', 'name_en' => 'Shirts', 'icon' => '👔'],
            ['name' => 'Celana', 'name_en' => 'Pants', 'icon' => '👖'],
            ['name' => 'Aksesoris', 'name_en' => 'Accessories', 'icon' => '🧢'],
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $model = Category::firstOrCreate(
                ['slug' => Str::slug($cat['name'])],
                ['name' => $cat['name'], 'name_en' => $cat['name_en'], 'icon' => $cat['icon']]
            );
            if ($model->name_en === null) {
                $model->update(['name_en' => $cat['name_en']]);
            }
            $categoryIds[$cat['name']] = $model->id;
        }

        $products = [
            [
                'name' => 'Essential Oversized Hoodie', 'category' => 'Hoodie',
                'price' => 429000, 'sale_price' => 349000,
                'colors' => [['Hitam', '#111110'], ['Krem', '#e7ddc9'], ['Abu', '#9a9691']],
                'stock' => ['S' => 6, 'M' => 10, 'L' => 8, 'XL' => 2],
                'rating' => 4.8, 'reviews' => 156, 'is_new' => false, 'bestseller' => true,
                'desc' => 'Hoodie oversized dengan fleece 340gsm brushed-interior. Dipotong dengan siluet drop-shoulder yang bersih, minim aksen, dan dirancang untuk tahan lama.',
                'desc_en' => 'Oversized hoodie with 340gsm brushed-interior fleece. Cut with a clean drop-shoulder silhouette, minimal accents, and designed to last.',
                'images' => ['https://picsum.photos/seed/frennz-f01a/900/1150', 'https://picsum.photos/seed/frennz-f01b/900/1150', 'https://picsum.photos/seed/frennz-f01c/900/1150'],
            ],
            [
                'name' => 'Signature Tee — Heavyweight', 'category' => 'T-Shirt',
                'price' => 219000, 'sale_price' => null,
                'colors' => [['Putih', '#ffffff'], ['Hitam', '#111110'], ['Sand', '#cbbfa4']],
                'stock' => ['S' => 14, 'M' => 18, 'L' => 12, 'XL' => 7],
                'rating' => 4.9, 'reviews' => 302, 'is_new' => true, 'bestseller' => true,
                'desc' => 'T-shirt garment-dyed 240gsm dengan jahitan rapi dan kerah yang tidak melar. Menjadi dasar dari setiap tampilan.',
                'desc_en' => "240gsm garment-dyed tee with neat stitching and a collar that won't stretch out. The foundation of every outfit.",
                'images' => ['https://picsum.photos/seed/frennz-f02a/900/1150', 'https://picsum.photos/seed/frennz-f02b/900/1150'],
            ],
            [
                'name' => 'Minimal Crewneck', 'category' => 'Crewneck',
                'price' => 379000, 'sale_price' => null,
                'colors' => [['Krem', '#e7ddc9'], ['Navy', '#28303f']],
                'stock' => ['M' => 6, 'L' => 6, 'XL' => 4],
                'rating' => 4.6, 'reviews' => 84, 'is_new' => false, 'bestseller' => false,
                'desc' => 'Crewneck fleece dengan rib tebal di kerah dan pergelangan. Dibuat tanpa cetakan besar — hanya bordir logo kecil di dada.',
                'desc_en' => 'Fleece crewneck with thick ribbing at the collar and cuffs. Made without big prints — just a small embroidered logo on the chest.',
                'images' => ['https://picsum.photos/seed/frennz-f03a/900/1150', 'https://picsum.photos/seed/frennz-f03b/900/1150'],
            ],
            [
                'name' => 'Relaxed Fit Shirt', 'category' => 'Kemeja',
                'price' => 329000, 'sale_price' => 279000,
                'colors' => [['Putih', '#ffffff'], ['Sage', '#a7b09a']],
                'stock' => ['S' => 4, 'M' => 0, 'L' => 5, 'XL' => 3],
                'rating' => 4.5, 'reviews' => 58, 'is_new' => false, 'bestseller' => false,
                'desc' => 'Kemeja katun relaxed-fit dengan drape ringan dan kancing tanduk. Cocok dikenakan longgar atau dimasukkan.',
                'desc_en' => 'Relaxed-fit cotton shirt with a light drape and horn buttons. Works worn loose or tucked in.',
                'images' => ['https://picsum.photos/seed/frennz-f04a/900/1150', 'https://picsum.photos/seed/frennz-f04b/900/1150'],
            ],
            [
                'name' => 'Tapered Cargo Pants', 'category' => 'Celana',
                'price' => 459000, 'sale_price' => null,
                'colors' => [['Hitam', '#111110'], ['Olive', '#5c5c47']],
                'stock' => ['29' => 5, '30' => 7, '32' => 6, '34' => 2],
                'rating' => 4.7, 'reviews' => 121, 'is_new' => false, 'bestseller' => true,
                'desc' => 'Celana cargo tapered dengan enam kantong fungsional dan bahan ripstop ringan tahan air.',
                'desc_en' => 'Tapered cargo pants with six functional pockets in lightweight water-resistant ripstop fabric.',
                'images' => ['https://picsum.photos/seed/frennz-f05a/900/1150', 'https://picsum.photos/seed/frennz-f05b/900/1150'],
            ],
            [
                'name' => 'Wide Straight Denim', 'category' => 'Celana',
                'price' => 499000, 'sale_price' => 419000,
                'colors' => [['Biru Washed', '#4a5b75']],
                'stock' => ['29' => 2, '30' => 4, '32' => 5, '34' => 3],
                'rating' => 4.7, 'reviews' => 69, 'is_new' => false, 'bestseller' => false,
                'desc' => 'Denim wide-straight dengan wash medium-blue dan finishing halus tanpa distressing berlebihan.',
                'desc_en' => 'Wide-straight denim in a medium-blue wash with a clean finish, free of excessive distressing.',
                'images' => ['https://picsum.photos/seed/frennz-f06a/900/1150', 'https://picsum.photos/seed/frennz-f06b/900/1150'],
            ],
            [
                'name' => 'Structured Cap', 'category' => 'Aksesoris',
                'price' => 179000, 'sale_price' => null,
                'colors' => [['Hitam', '#111110'], ['Krem', '#e7ddc9']],
                'stock' => ['ALL SIZE' => 22],
                'rating' => 4.8, 'reviews' => 97, 'is_new' => true, 'bestseller' => false,
                'desc' => 'Topi structured six-panel dengan bordir logo minimal dan strap belakang yang dapat disesuaikan.',
                'desc_en' => 'Structured six-panel cap with minimal logo embroidery and an adjustable rear strap.',
                'images' => ['https://picsum.photos/seed/frennz-f07a/900/1150', 'https://picsum.photos/seed/frennz-f07b/900/1150'],
            ],
            [
                'name' => 'Canvas Tote', 'category' => 'Aksesoris',
                'price' => 149000, 'sale_price' => null,
                'colors' => [['Natural', '#d8cfb8']],
                'stock' => ['ALL SIZE' => 30],
                'rating' => 4.6, 'reviews' => 44, 'is_new' => false, 'bestseller' => false,
                'desc' => 'Tote bag kanvas tebal 12oz dengan tali panjang dan cetakan wordmark kecil di depan.',
                'desc_en' => 'Heavy 12oz canvas tote with long straps and a small wordmark print on the front.',
                'images' => ['https://picsum.photos/seed/frennz-f08a/900/1150', 'https://picsum.photos/seed/frennz-f08b/900/1150'],
            ],
            [
                'name' => 'Track Jacket Minimal', 'category' => 'Hoodie',
                'price' => 449000, 'sale_price' => null,
                'colors' => [['Hitam', '#111110'], ['Krem', '#e7ddc9']],
                'stock' => ['S' => 5, 'M' => 7, 'L' => 6, 'XL' => 3],
                'rating' => 4.6, 'reviews' => 39, 'is_new' => true, 'bestseller' => false,
                'desc' => 'Jaket track dengan resleting penuh, panel tanpa jahitan mencolok, dan lapisan dalam tricot ringan.',
                'desc_en' => 'Track jacket with a full zip, clean seam-free panels, and light tricot lining.',
                'images' => ['https://picsum.photos/seed/frennz-f09a/900/1150', 'https://picsum.photos/seed/frennz-f09b/900/1150'],
            ],
            [
                'name' => 'Ribbed Socks Set', 'category' => 'Aksesoris',
                'price' => 99000, 'sale_price' => 79000,
                'colors' => [['Mix', '#cbbfa4']],
                'stock' => ['ALL SIZE' => 48],
                'rating' => 4.8, 'reviews' => 133, 'is_new' => false, 'bestseller' => true,
                'desc' => 'Satu set tiga pasang kaos kaki rib tinggi dengan logo woven kecil di bagian atas.',
                'desc_en' => 'A set of three pairs of high rib socks with a small woven logo at the top.',
                'images' => ['https://picsum.photos/seed/frennz-f10a/900/1150', 'https://picsum.photos/seed/frennz-f10b/900/1150'],
            ],
            [
                'name' => 'Boxy Tee Washed', 'category' => 'T-Shirt',
                'price' => 229000, 'sale_price' => null,
                'colors' => [['Abu', '#9a9691'], ['Hitam', '#111110']],
                'stock' => ['S' => 6, 'M' => 6, 'L' => 6, 'XL' => 6],
                'rating' => 4.4, 'reviews' => 27, 'is_new' => false, 'bestseller' => false,
                'desc' => 'T-shirt boxy-fit dengan proses garment wash agar tekstur terasa lebih lembut sejak pemakaian pertama.',
                'desc_en' => 'Boxy-fit tee, garment-washed for a softer texture from the very first wear.',
                'images' => ['https://picsum.photos/seed/frennz-f11a/900/1150', 'https://picsum.photos/seed/frennz-f11b/900/1150'],
            ],
            [
                'name' => 'Quilted Vest', 'category' => 'Hoodie',
                'price' => 399000, 'sale_price' => null,
                'colors' => [['Hitam', '#111110']],
                'stock' => ['S' => 3, 'M' => 4, 'L' => 4, 'XL' => 1],
                'rating' => 4.5, 'reviews' => 22, 'is_new' => true, 'bestseller' => false,
                'desc' => 'Rompi quilted ringan dengan resleting penuh, cocok dilayer di atas hoodie atau kemeja.',
                'desc_en' => 'Lightweight quilted vest with a full zip — perfect layered over a hoodie or shirt.',
                'images' => ['https://picsum.photos/seed/frennz-f12a/900/1150', 'https://picsum.photos/seed/frennz-f12b/900/1150'],
            ],
        ];

        foreach ($products as $index => $p) {
            $product = Product::firstOrCreate(
                ['slug' => Str::slug($p['name'])],
                [
                    'category_id' => $categoryIds[$p['category']],
                    'name' => $p['name'],
                    'price' => $p['price'],
                    'sale_price' => $p['sale_price'],
                    'description' => $p['desc'],
                    'description_en' => $p['desc_en'],
                    'rating' => $p['rating'],
                    'reviews_count' => $p['reviews'],
                    'is_new' => $p['is_new'],
                    'is_best_seller' => $p['bestseller'],
                    'status' => 'published',
                ]
            );

            if ($product->description_en === null) {
                $product->update(['description_en' => $p['desc_en']]);
            }

            if ($product->images()->count() === 0) {
                foreach ($p['images'] as $i => $url) {
                    $product->images()->create(['image_path' => $url, 'sort_order' => $i]);
                }
            }

            if ($product->variants()->count() === 0) {
                $sku = 'FZ-' . Str::upper(Str::random(6));
                foreach ($p['stock'] as $size => $stock) {
                    foreach ($p['colors'] as $j => [$colorName, $colorHex]) {
                        $product->variants()->create([
                            'size' => $size,
                            'color_name' => $colorName,
                            'color_hex' => $colorHex,
                            'stock' => $stock,
                            'sku' => $sku . '-' . $size . '-' . ($j + 1),
                        ]);
                    }
                }
            }
        }
    }
}