<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('product', function ($builder) {
            $builder->with('product');
        });
    }


    protected $fillable = [
        'buy_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    // Relación con el modelo Sale
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    // Relación con el modelo Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
