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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id'); // ID de la compra
            $table->unsignedBigInteger('product_id'); // ID del producto comprado
            $table->integer('quantity'); // Cantidad comprada del producto
            $table->decimal('price', 10, 2); // Precio del producto
            $table->decimal('subtotal', 10, 2); // Subtotal (cantidad * precio)
            $table->timestamps();

            // Llaves foráneas
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
