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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('rfc');
            $table->string('business_name');
            $table->string('fiscal_regime');
            $table->string('cfdi_use');
            $table->text('street')->nullable();
            $table->string('exterior_number')->nullable();
            $table->string('interior_number')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('locality')->nullable();
            $table->string('municipality')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('email')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('iva', 10, 2); 
            $table->decimal('total_amount', 10, 2); 
            $table->string('payment_method');
            $table->string('last_digits_card')->nullable();
            $table->string('folio')->nullable();
            $table->string('status')->default("pending");
            $table->string('facturama_id')->nullable();  
            $table->string('facturama_url')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
