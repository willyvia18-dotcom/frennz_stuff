<?php

return [
    'required' => 'The :attribute field is required.',
    'email' => 'The :attribute must be a valid email address.',
    'numeric' => 'The :attribute must be a number.',
    'integer' => 'The :attribute must be an integer.',
    'boolean' => 'The :attribute field must be true or false.',
    'array' => 'The :attribute must be an array.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'max' => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'string' => 'The :attribute may not be greater than :max characters.',
    ],
    'confirmed' => 'The :attribute confirmation does not match.',
    'unique' => 'The :attribute has already been taken.',
    'in' => 'The selected :attribute is invalid.',

    'attributes' => [
        'name' => 'name',
        'email' => 'email',
        'password' => 'password',
        'phone' => 'phone number',
        'address' => 'address',
        'city' => 'city',
        'postal' => 'postal code',
        'courier' => 'courier',
        'payment' => 'payment method',
        'items' => 'order items',
        'category' => 'category',
        'price' => 'price',
        'sale_price' => 'sale price',
        'sizes' => 'sizes',
        'stock_list' => 'stock',
        'colors' => 'colors',
        'images' => 'images',
        'desc' => 'description',
        'icon' => 'icon',
    ],
];
