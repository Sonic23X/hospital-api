<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'supplier_id',
        'patient_id',
        'sale_id',
        'rfc',
        'business_name', 
        'fiscal_regime', 
        'cfdi_use', 
        'zipcode', 
        'street', 
        'exterior_number', 
        'interior_number', 
        'neighborhood', 
        'locality', 
        'municipality', 
        'state', 
        'email', 
        'subtotal', 
        'iva', 
        'total_amount', 
        'payment_method', 
        'last_digits_card', 
        'status',
        'folio',
        'facturama_id', 
        'facturama_url'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    // 01: Efectivo
    // 02: Cheque nominativo
    // 03: Transferencia electrónica de fondos
    // 04: Tarjeta de crédito
    // 05: Monedero electrónico
    // 06: Dinero electrónico
    // 08: Vales de despensa
    // 12: Dación en pago
    // 28: Tarjeta de débito
    // 29: Tarjeta de servicios
    // 99: Por definir 

}
