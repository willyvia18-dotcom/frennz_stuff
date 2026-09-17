<?php

return [
    'required' => 'Kolom :attribute wajib diisi.',
    'email' => ':attribute harus berupa alamat email yang valid.',
    'numeric' => ':attribute harus berupa angka.',
    'integer' => ':attribute harus berupa bilangan bulat.',
    'boolean' => ':attribute harus bernilai true atau false.',
    'array' => ':attribute harus berupa array.',
    'min' => [
        'numeric' => ':attribute minimal :min.',
        'string' => ':attribute minimal :min karakter.',
    ],
    'max' => [
        'numeric' => ':attribute maksimal :max.',
        'string' => ':attribute maksimal :max karakter.',
    ],
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'unique' => ':attribute sudah digunakan.',
    'in' => 'Nilai :attribute tidak valid.',

    'attributes' => [
        'name' => 'nama',
        'email' => 'email',
        'password' => 'kata sandi',
        'phone' => 'nomor HP',
        'address' => 'alamat',
        'city' => 'kota',
        'postal' => 'kode pos',
        'courier' => 'jasa ekspedisi',
        'payment' => 'metode pembayaran',
        'items' => 'item pesanan',
        'category' => 'kategori',
        'price' => 'harga',
        'sale_price' => 'harga diskon',
        'sizes' => 'ukuran',
        'stock_list' => 'stok',
        'colors' => 'warna',
        'images' => 'foto',
        'desc' => 'deskripsi',
        'icon' => 'ikon',
    ],
];
