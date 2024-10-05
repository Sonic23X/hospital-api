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
        Schema::create('customer_billing_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('rfc');
            $table->string('business_name');
            $table->string('fiscal_regime');
            $table->string('cfdi_use');
            $table->text('street');
            $table->string('exterior_number');
            $table->string('interior_number');
            $table->string('neighborhood');
            $table->string('locality');
            $table->string('municipality');
            $table->string('state');
            $table->string('zipcode');
            $table->string('email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_billing_data');
    }
};
