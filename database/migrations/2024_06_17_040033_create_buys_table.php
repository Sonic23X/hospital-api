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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID del usuario que realiza la compra
            $table->unsignedBigInteger('client_id'); // ID de la farmacia
            $table->decimal('subtotal', 10, 2); // Monto total de la compra (sin impuestos)
            $table->decimal('iva', 10, 2); // Impuestos aplicados a la compra
            $table->decimal('total_amount', 10, 2); // Monto total de la compra
            $table->dateTime('purchase_date'); // Fecha y hora de la compra
            $table->string('payment_method'); // Método de pago (efectivo, tarjeta, etc.)
            $table->string('status'); // Estado de la compra (pendiente, completada, cancelada, etc.)
            $table->text('notes')->nullable(); // Notas adicionales sobre la compra
            $table->string('email_invoice');
            $table->timestamps(); // Campos created_at y updated_at

            // Llaves foráneas
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('client_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
