<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')
                ->constrained()->nullOnDelete();

            $table->string('buyer_name');
            $table->string('buyer_phone', 30);
            $table->string('buyer_email');

            $table->string('address_line');
            $table->string('address_city', 120);
            $table->string('address_postal', 12);

            $table->string('courier', 30);
            $table->string('payment_method', 30);

            $table->string('voucher_code')->nullable();

            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            $table->enum('status', ['pending', 'processing', 'shipped', 'completed', 'cancelled'])
                ->default('pending');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id', 'buyer_name', 'buyer_phone', 'buyer_email',
                'address_line', 'address_city', 'address_postal',
                'courier', 'payment_method', 'voucher_code',
                'shipping_cost', 'discount', 'total', 'status',
            ]);
        });
    }
};