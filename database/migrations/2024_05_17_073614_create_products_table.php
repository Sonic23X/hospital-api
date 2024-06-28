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
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->integer('stock');
            $table->string('category')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('batch')->nullable();
            $table->string('active_substance')->nullable();
            $table->string('barcode')->nullable();
            $table->string('qr_location')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_pharmaceutical')->default(false);
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('invoice_type', 5);
            $table->unsignedBigInteger('client_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
