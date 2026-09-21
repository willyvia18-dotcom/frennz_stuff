<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'essential-oversized-hoodie' => [
                '/images/products/essential-oversized-hoodie-1.jpg',
                '/images/products/essential-oversized-hoodie-2.jpg',
                '/images/products/essential-oversized-hoodie-3.jpg',
            ],
            'signature-tee-heavyweight' => [
                '/images/products/signature-tee-heavyweight-1.jpg',
                '/images/products/signature-tee-heavyweight-2.jpg',
            ],
            'minimal-crewneck' => [
                '/images/products/minimal-crewneck-1.jpg',
                '/images/products/minimal-crewneck-2.jpg',
            ],
            'relaxed-fit-shirt' => [
                '/images/products/relaxed-fit-shirt-1.jpg',
                '/images/products/relaxed-fit-shirt-2.jpg',
            ],
            'tapered-cargo-pants' => [
                '/images/products/tapered-cargo-pants-1.jpg',
                '/images/products/tapered-cargo-pants-2.jpg',
            ],
            'wide-straight-denim' => [
                '/images/products/wide-straight-denim-1.jpg',
                '/images/products/wide-straight-denim-2.jpg',
            ],
            'structured-cap' => [
                '/images/products/structured-cap-1.jpg',
                '/images/products/structured-cap-2.jpg',
            ],
            'canvas-tote' => [
                '/images/products/canvas-tote-1.jpg',
                '/images/products/canvas-tote-2.jpg',
            ],
            'track-jacket-minimal' => [
                '/images/products/track-jacket-minimal-1.jpg',
                '/images/products/track-jacket-minimal-2.jpg',
            ],
            'ribbed-socks-set' => [
                '/images/products/ribbed-socks-set-1.jpg',
                '/images/products/ribbed-socks-set-2.jpg',
            ],
            'boxy-tee-washed' => [
                '/images/products/boxy-tee-washed-1.jpg',
                '/images/products/boxy-tee-washed-2.jpg',
            ],
            'quilted-vest' => [
                '/images/products/quilted-vest-1.jpg',
                '/images/products/quilted-vest-2.jpg',
            ],
        ];

        foreach ($map as $slug => $images) {
            $product = Product::where('slug', $slug)->first();
            if (! $product) {
                continue;
            }

            $product->images()->delete();

            foreach ($images as $i => $url) {
                $product->images()->create(['image_path' => $url, 'sort_order' => $i]);
            }
        }
    }
}