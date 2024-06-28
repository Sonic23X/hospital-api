<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'client_id',
        'subtotal',
        'iva',
        'total_amount',
        'purchase_date',
        'payment_method',
        'status',
        'notes',
    ];

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con el modelo SaleItem
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
