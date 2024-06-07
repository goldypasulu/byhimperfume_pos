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
        Schema::create('stock_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->foreignId('branch_id')->nullable()->constrained('branches');
            $table->foreignId('fragrance_id')->nullable()->constrained('fragrances');
            $table->integer('stock_in')->nullable();
            $table->integer('stock_out')->nullable();
            $table->integer('stock_balance')->nullable();
            $table->date('stock_opname_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_cards');
    }
};
