<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category',
        'expiry_date',
        'batch',
        'active_substance',
        'barcode',
        'qr_location',
        'images',
        'is_pharmaceutical',
        'supplier_id',
        'client_id',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
