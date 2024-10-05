<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerBillingData extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id',
     'supplier_id',
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
     'email'
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
}
