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
                'https://picsum.photos/seed/frennz-f01a/900/1150',
                'https://picsum.photos/seed/frennz-f01b/900/1150',
                'https://picsum.photos/seed/frennz-f01c/900/1150',
            ],
            'signature-tee-heavyweight' => [
                'https://picsum.photos/seed/frennz-f02a/900/1150',
                'https://picsum.photos/seed/frennz-f02b/900/1150',
            ],
            'minimal-crewneck' => [
                'https://picsum.photos/seed/frennz-f03a/900/1150',
                'https://picsum.photos/seed/frennz-f03b/900/1150',
            ],
            'relaxed-fit-shirt' => [
                'https://picsum.photos/seed/frennz-f04a/900/1150',
                'https://picsum.photos/seed/frennz-f04b/900/1150',
            ],
            'tapered-cargo-pants' => [
                'https://picsum.photos/seed/frennz-f05a/900/1150',
                'https://picsum.photos/seed/frennz-f05b/900/1150',
            ],
            'wide-straight-denim' => [
                'https://picsum.photos/seed/frennz-f06a/900/1150',
                'https://picsum.photos/seed/frennz-f06b/900/1150',
            ],
            'structured-cap' => [
                'https://picsum.photos/seed/frennz-f07a/900/1150',
                'https://picsum.photos/seed/frennz-f07b/900/1150',
            ],
            'canvas-tote' => [
                'https://picsum.photos/seed/frennz-f08a/900/1150',
                'https://picsum.photos/seed/frennz-f08b/900/1150',
            ],
            'track-jacket-minimal' => [
                'https://picsum.photos/seed/frennz-f09a/900/1150',
                'https://picsum.photos/seed/frennz-f09b/900/1150',
            ],
            'ribbed-socks-set' => [
                'https://picsum.photos/seed/frennz-f10a/900/1150',
                'https://picsum.photos/seed/frennz-f10b/900/1150',
            ],
            'boxy-tee-washed' => [
                'https://picsum.photos/seed/frennz-f11a/900/1150',
                'https://picsum.photos/seed/frennz-f11b/900/1150',
            ],
            'quilted-vest' => [
                'https://picsum.photos/seed/frennz-f12a/900/1150',
                'https://picsum.photos/seed/frennz-f12b/900/1150',
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