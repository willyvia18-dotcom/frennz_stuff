<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $vouchers = [
            ['code' => 'FRENNZ10', 'type' => 'percent', 'value' => 10, 'description' => 'Diskon 10% semua produk'],
            ['code' => 'NEWMEMBER', 'type' => 'fixed', 'value' => 25000, 'description' => 'Potongan Rp25.000 untuk pembeli baru'],
        ];

        foreach ($vouchers as $v) {
            Voucher::firstOrCreate(['code' => $v['code']], $v + ['is_active' => true]);
        }
    }
}