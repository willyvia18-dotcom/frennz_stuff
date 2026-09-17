<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('name');
            $table->string('slug')->unique();

            $table->decimal('price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();

            $table->text('description')->nullable();

            $table->decimal('rating', 3, 2)->default(0);
            $table->unsignedInteger('reviews_count')->default(0);

            $table->boolean('is_new')->default(false);
            $table->boolean('is_best_seller')->default(false);

            $table->enum('status', [
                'draft',
                'published',
                'hidden'
            ])->default('published');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};