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
        // Foto produk memakai file lokal tetap di public/images/products/
        // (bukan URL acak). Sumber foto: Wikimedia Commons (CC0/CC BY/CC BY-SA),
        // Unsplash & Pexels License — bebas dipakai untuk demo.
        // Brand: Adidas & Nike — 6 produk masing-masing, warna disesuaikan palet brand.
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

        // 6 Nike + 6 Adidas — slug tetap sama agar tidak duplikat, tapi nama & brand di-update
        $products = [
            [
                'slug' => 'essential-oversized-hoodie', 'brand' => 'Thankinsomnia',
                'name' => 'Hoodie Ovania', 'category' => 'Hoodie',
                'price' => 449000, 'sale_price' => 349000,
                'colors' => [['Hitam', '#111111'], ['Abu', '#9A9691'], ['Sail', '#CBBFA4']],
                'stock' => ['S' => 6, 'M' => 10, 'L' => 8, 'XL' => 2],
                'rating' => 4.8, 'reviews' => 156, 'is_new' => false, 'bestseller' => true,
                'desc' => 'Hoodie Ovania dari Thankinsomnia dengan fleece 340gsm dan potongan oversized. Nyaman untuk layering harian.',
                'desc_en' => 'Hoodie Ovania by Thankinsomnia with 340gsm fleece and oversized cut. Comfortable for daily layering.',
                'images' => ['/images/products/essential-oversized-hoodie-1.jpg', '/images/products/essential-oversized-hoodie-2.jpg', '/images/products/essential-oversized-hoodie-3.jpg'],
            ],
            [
                'slug' => 'signature-tee-heavyweight', 'brand' => 'Stone Island',
                'name' => 'Long Sleeve Polo Stone Island', 'category' => 'T-Shirt',
                'price' => 299000, 'sale_price' => null,
                'colors' => [['Putih', '#FFFFFF'], ['Navy', '#1E3A5F']],
                'stock' => ['S' => 14, 'M' => 18, 'L' => 12, 'XL' => 7],
                'rating' => 4.9, 'reviews' => 302, 'is_new' => true, 'bestseller' => true,
                'desc' => 'Long Sleeve Polo Stone Island dengan compass patch ikonik di lengan dan bahan pique cotton garment-dyed yang lembut dan breathable. Kerah polo rib yang kokoh, potongan regular fit yang premium untuk tampilan smart casual.',
                'desc_en' => 'Stone Island Long Sleeve Polo with iconic compass patch on the sleeve and soft breathable garment-dyed pique cotton. Sturdy ribbed polo collar, premium regular fit for smart casual look.',
                'images' => ['/images/products/signature-tee-heavyweight-1.jpg', '/images/products/signature-tee-heavyweight-2.jpg'],
            ],
            [
                'slug' => 'minimal-crewneck', 'brand' => 'Stone Island',
                'name' => 'Crewneck Stone Island', 'category' => 'Crewneck',
                'price' => 949000, 'sale_price' => 849000,
                'colors' => [['Hitam', '#111111'], ['Abu', '#9A9691']],
                'stock' => ['M' => 6, 'L' => 6, 'XL' => 4],
                'rating' => 4.9, 'reviews' => 302, 'is_new' => false, 'bestseller' => true,
                'desc' => 'Crewneck Stone Island garment-dyed dengan compass patch ikonik di lengan dan bahan loopback 460gsm. Hangat, breathable, dan potongan regular fit yang premium.',
                'desc_en' => 'Stone Island garment-dyed crewneck with iconic compass patch on the sleeve and 460gsm loopback cotton. Warm, breathable, and premium regular fit.',
                'images' => ['/images/products/minimal-crewneck-1.jpg', '/images/products/minimal-crewneck-2.jpg'],
            ],
            [
                'slug' => 'relaxed-fit-shirt', 'brand' => 'Lyle and Scott',
                'name' => 'Kemeja Lyle and Scott', 'category' => 'Kemeja',
                'price' => 459000, 'sale_price' => 379000,
                'colors' => [['Putih', '#FFFFFF'], ['Sage', '#9CAF88']],
                'stock' => ['S' => 4, 'M' => 0, 'L' => 5, 'XL' => 3],
                'rating' => 4.5, 'reviews' => 58, 'is_new' => false, 'bestseller' => false,
                'desc' => 'Kemeja Lyle and Scott Oxford dengan bordir eagle ikonik di dada, bahan cotton poplin lembut dan breathable. Potongan regular fit yang rapi untuk tampilan smart casual.',
                'desc_en' => 'Lyle and Scott Oxford shirt with iconic eagle embroidery at chest, soft breathable cotton poplin. Regular fit for smart casual look.',
                'images' => ['/images/products/relaxed-fit-shirt-1.jpg', '/images/products/relaxed-fit-shirt-2.jpg'],
            ],
            [
                'slug' => 'tapered-cargo-pants', 'brand' => 'Adidas',
                'name' => 'Trackpants Adidas', 'category' => 'Celana',
                'price' => 599000, 'sale_price' => null,
                'colors' => [['Hitam', '#000000'], ['Navy', '#1E3A5F']],
                'stock' => ['29' => 5, '30' => 7, '32' => 6, '34' => 2],
                'rating' => 4.7, 'reviews' => 121, 'is_new' => false, 'bestseller' => true,
                'desc' => 'Trackpants Adidas dengan potongan tapered yang nyaman dan 3-stripes ikonik di sisi. Bahan double-knit lembut dengan manset elastis untuk tampilan sporty dan mobilitas harian.',
                'desc_en' => 'Adidas trackpants with a comfortable tapered cut and iconic 3-stripes on the sides. Soft double-knit fabric with elastic cuffs for a sporty look and daily mobility.',
                'images' => ['/images/products/tapered-cargo-pants-1.jpg', '/images/products/tapered-cargo-pants-2.jpg'],
            ],
            [
                'slug' => 'wide-straight-denim', 'brand' => 'Thankinsomnia',
                'name' => 'Denim Pants Dralle', 'category' => 'Celana',
                'price' => 649000, 'sale_price' => 549000,
                'colors' => [['Indigo', '#2F3A56'], ['Hitam', '#000000']],
                'stock' => ['29' => 2, '30' => 4, '32' => 5, '34' => 3],
                'rating' => 4.7, 'reviews' => 69, 'is_new' => false, 'bestseller' => false,
                'desc' => 'Denim Pants Dralle dari Thankinsomnia dengan potongan wide straight dan wash indigo premium. Bahan denim 12oz yang kokoh namun nyaman untuk daily wear.',
                'desc_en' => 'Thankinsomnia Denim Pants Dralle with wide straight cut and premium indigo wash. Sturdy 12oz denim yet comfortable for daily wear.',
                'images' => ['/images/products/wide-straight-denim-1.jpg', '/images/products/wide-straight-denim-2.jpg'],
            ],
            [
                'slug' => 'structured-cap', 'brand' => 'Thankinsomnia',
                'name' => 'Hat Rebellia', 'category' => 'Aksesoris',
                'price' => 249000, 'sale_price' => null,
                'colors' => [['Hitam', '#000000'], ['Krem', '#F5F0E6']],
                'stock' => ['ALL SIZE' => 22],
                'rating' => 4.8, 'reviews' => 97, 'is_new' => true, 'bestseller' => false,
                'desc' => 'Hat Rebellia dari Thankinsomnia dengan desain 6-panel, bordir logo Thankinsomnia dan strap adjustable di belakang. Bahan twill premium yang ringan dan nyaman.',
                'desc_en' => 'Thankinsomnia Hat Rebellia with 6-panel design, Thankinsomnia logo embroidery and adjustable rear strap. Premium lightweight twill.',
                'images' => ['/images/products/structured-cap-1.jpg', '/images/products/structured-cap-2.jpg'],
            ],
            [
                'slug' => 'canvas-tote', 'brand' => 'Thankinsomnia',
                'name' => 'Tote Bag Floresca', 'category' => 'Aksesoris',
                'price' => 199000, 'sale_price' => null,
                'colors' => [['Natural', '#D8CFB8']],
                'stock' => ['ALL SIZE' => 30],
                'rating' => 4.6, 'reviews' => 44, 'is_new' => false, 'bestseller' => false,
                'desc' => 'Tote Bag Floresca dari Thankinsomnia berbahan kanvas 14oz dengan sablon grafis khas Thankinsomnia dan tali panjang yang kokoh. Kapasitas luas untuk daily essentials.',
                'desc_en' => 'Thankinsomnia Tote Bag Floresca in 14oz canvas with signature Thankinsomnia graphic print and sturdy long straps. Spacious for daily essentials.',
                'images' => ['/images/products/canvas-tote-1.jpg', '/images/products/canvas-tote-2.jpg'],
            ],
            [
                'slug' => 'track-jacket-minimal', 'brand' => 'Adidas',
                'name' => 'Tracktop Adidas', 'category' => 'Hoodie',
                'price' => 649000, 'sale_price' => null,
                'colors' => [['Hitam', '#000000'], ['Navy', '#1E3A5F']],
                'stock' => ['S' => 5, 'M' => 7, 'L' => 6, 'XL' => 3],
                'rating' => 4.6, 'reviews' => 39, 'is_new' => true, 'bestseller' => false,
                'desc' => 'Tracktop Adidas dengan 3-stripes ikonik di lengan dan bahan tricot halus.',
                'desc_en' => 'Adidas tracktop with iconic 3-stripes on sleeves and smooth tricot.',
                'images' => ['/images/products/track-jacket-minimal-1.jpg', '/images/products/track-jacket-minimal-2.jpg'],
            ],
            [
                'slug' => 'ribbed-socks-set', 'brand' => 'Thankinsomnia',
                'name' => 'Crew Sock Luma', 'category' => 'Aksesoris',
                'price' => 69000, 'sale_price' => 49000,
                'colors' => [['Putih', '#FFFFFF'], ['Hitam', '#111111']],
                'stock' => ['ALL SIZE' => 48],
                'rating' => 4.8, 'reviews' => 133, 'is_new' => false, 'bestseller' => true,
                'desc' => 'Paket 3 kaos kaki Crew Sock Luma dari Thankinsomnia dengan bantalan nyaman untuk pemakaian harian. Tersedia dalam warna Putih dan Hitam.',
                'desc_en' => 'Thankinsomnia Crew Sock Luma 3-pack with comfortable cushioning for daily wear. Available in White and Black.',
                'images' => ['/images/products/ribbed-socks-set-1.jpg', '/images/products/ribbed-socks-set-2.jpg'],
            ],
            [
                'slug' => 'boxy-tee-washed', 'brand' => 'Thankinsomnia',
                'name' => 'Tshirt Fosca Black Thankinsomnia', 'category' => 'T-Shirt',
                'price' => 299000, 'sale_price' => null,
                'colors' => [['Hitam', '#000000']],
                'stock' => ['S' => 6, 'M' => 6, 'L' => 6, 'XL' => 6],
                'rating' => 4.4, 'reviews' => 27, 'is_new' => false, 'bestseller' => false,
                'desc' => 'Tshirt Fosca Black Thankinsomnia boxy-fit garment-washed. Warna Hitam.',
                'desc_en' => 'Thankinsomnia Fosca Black boxy garment-washed tee. Black color.',
                'images' => ['/images/products/boxy-tee-washed-1.jpg', '/images/products/boxy-tee-washed-2.jpg'],
            ],
            [
                'slug' => 'quilted-vest', 'brand' => 'C.P. Company',
                'name' => 'C.P. Shell-R Google Jacket', 'category' => 'Hoodie',
                'price' => 549000, 'sale_price' => null,
                'colors' => [['Hitam', '#000000']],
                'stock' => ['S' => 3, 'M' => 4, 'L' => 4, 'XL' => 1],
                'rating' => 4.5, 'reviews' => 22, 'is_new' => true, 'bestseller' => false,
                'desc' => 'Jaket C.P. Shell-R Google Jacket dari C.P. Company dengan lensa goggle ikonik di lengan. Cocok layering di atas hoodie.',
                'desc_en' => 'C.P. Company C.P. Shell-R Google Jacket with iconic goggle lens on the sleeve. Perfect layering over hoodie.',
                'images' => ['/images/products/quilted-vest-1.jpg', '/images/products/quilted-vest-2.jpg'],
            ],
        ];

        foreach ($products as $p) {
            $product = Product::where('slug', $p['slug'])->first();

            if (! $product) {
                $product = Product::create([
                    'slug' => $p['slug'],
                    'brand' => $p['brand'],
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
                ]);
            } else {
                $product->update([
                    'brand' => $p['brand'],
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
                ]);
            }

            // Reset images & variants to brand-correct data
            $product->images()->delete();
            foreach ($p['images'] as $i => $url) {
                $product->images()->create(['image_path' => $url, 'sort_order' => $i]);
            }

            $product->variants()->delete();
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
