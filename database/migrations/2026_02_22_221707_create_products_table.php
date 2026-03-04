<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('metal', ['gold', 'silver']);
            $table->enum('category', ['coin', 'bar']);
            $table->decimal('weight', 8, 2);
            $table->string('weight_unit')->default('oz');
            $table->decimal('purity', 5, 4);
            $table->decimal('premium_percentage', 5, 2)->default(0);
            $table->decimal('premium_fixed', 10, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
